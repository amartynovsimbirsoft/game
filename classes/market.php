<?php

class Market
{
    /** @var array of Company objects
     * Список компаний рынка
     */
    public array $companies = [];

    /** @var int
     * Сумма всех запросов от компаний на найм, т.е. сколько всего компании хотят
     * нанять сотрудников за этот ход
     */
    public int $hire_sum = 0;

    /** @var int
     * Сумма всех запросов от компаний на увольнения, т.е. сколько суммарно на этом ходу
     * компании хотят уволить сотрудников
     */
    public int $fire_sum = 0;

    /** @var int
     * Общая сумма всех сотрудников во всех компаниях рынка
     */
    public int $empl_sum = 0;

    /** @var int
     * Общая сумма всех инвестиций в iHR со всех компаний на это ходу
     */
    public int $iHR_sum = 0;

    /** @var int
     * Общая сумма всех инвестиций в iSales со всех компаний на это ходу
     */
    public int $iSales_sum = 0;

    /** @var int
     * Общая сумма всех инвестиций в iAccount со всех компаний на это ходу
     */
    public int $iAccount_sum = 0;

    /** @var int
     * Общая сумма всех инвестиций в iPE (эффективность производства) со всех компаний на это ходу
     */
    public int $iPE_sum = 0;

    /** @var float|int
     * Вычисленный коэффициент обозначаются спрос на кадры в отрасли, чем выше значение, тем выше спрос
     * Показывает, на сколько процентов "хочет" вырасти общая численность персонала во всех компаниях.
     * Зависит от суммы общего найма всех компаний и от общей численности всех сотрудников.
     */
    public float $empl_demand = 0;

    public Factors $factors;

    public function __construct(Factors $factors)
    {
        $this->factors = $factors;
    }

    public function __clone()
    {
        foreach($this->companies as $key=>$company) {
            $this->companies[$key] = clone $company;
        }
        $this->factors = clone $this->factors;
    }

    /**
     * Добавляем компанию "в рынок"
     */
    public function addCompany(Company $company)
    {
        $this->companies[] = $company;
    }

    /**
     * Опрашиваем каждую компанию и "просим" загрузить ее данные хода
     * Сделано таким образом с целью, что ход игроков будет загружаться из файла по name компании
     * Если кто-то из игроков не предоставил файл - компания выкинет исключение и работа скрипта
     * остановится, таким образом обсчета хода не произойдет
     * Это позволит запускать скрипт много раз, пока не состоится полная загрузка данных всех игроков.
     */
    public function loadCompaniesTurnData()
    {
        foreach ($this->companies as $company) {
            $company->turn->loadTurnData();
        }
    }

    /**
     * Вычисляем вычислимые значения Рынка на основе данных компаний: их атрибутов и ходов
     * Определяются такие величины, как общий спрос на кадры, спрос на услуги и тд
     */
    public function updateComputableValues()
    {
        $this->fire_sum = 0;
        $this->hire_sum = 0;
        $this->empl_sum = 0;
        $this->iHR_sum = 0;
        $this->iSales_sum = 0;
        $this->iAccount_sum = 0;
        $this->iPE_sum = 0;
        $this->empl_demand = 0;

        foreach ($this->companies as $company) {
            $this->fire_sum += $company->turn->fire;
            $this->hire_sum += $company->turn->hire;
            $this->empl_sum += $company->attr->empl;
            $this->iHR_sum += $company->turn->iHR;
            $this->iSales_sum += $company->turn->iSales;
            $this->iAccount_sum += $company->turn->iAccount;
            $this->iPE_sum += $company->turn->iPE;
        }

        if ($this->empl_sum != 0) {
            // Делаем спрос зависимым от рыночной активности
            $market_activity = ($this->iSales_sum + $this->iAccount_sum) / ($this->empl_sum + 1);
            $this->empl_demand = ($this->hire_sum / ($this->empl_sum + 1)) 
                   * (0.5 + 0.5 * tanh($market_activity - 0.7));
        }
        else {
            $this->empl_demand = 0;
        }
    }

    public function updateCompanies()
    {
        foreach ($this->companies as $company) {
            $company->calc = new CompanyCalc();

            // вычисляем базовые вычислимые значения
            $company->calc->empl_billable = floor($company->attr->empl * $company->attr->kz);
            $company->calc->empl_notbillable = $company->attr->empl - $company->calc->empl_billable;

            // нормализованные значения инвестиций, от суммы всех инвестиций всех компаний
            // например, если значение x_iHR будет 0.5, это означает, что эта компания делает 50%
            // всех инвестиций в отрасли, что очень много

            // Вместо линейной отдачи зависимости найма от вложений добавил логарифмическую зависимость
            if ($this->iHR_sum != 0)        $company->calc->x_iHR       = $company->calc->x_iHR = log(1 + $company->turn->iHR) / log(1 + $this->iHR_sum);
            // Добавляем "эффективность маркетинга" с убывающей отдачей
            if ($this->iSales_sum !=0) {
                $sales_efficiency = 1 - exp(-0.005 * $company->turn->iSales);
                $company->calc->x_iSales = $sales_efficiency * sqrt($company->turn->iSales) / $this->iSales_sum;
            }      
            // Учитываем масштаб компании (большим компаниям сложнее расти %)
            if ($this->iAccount_sum != 0) {
                $account_multiplier = 1 / (1 + 0.01 * $company->attr->empl);
                $company->calc->x_iAccount = $account_multiplier * pow($company->turn->iAccount, 0.8) / pow($this->iAccount_sum, 0.9);
            }  
            // S-образная кривая отдачи
            if ($this->iPE_sum != 0) {
                $pe_effect = 1 / (1 + exp(-0.01 * ($company->turn->iPE - $this->iPE_sum / count($this->companies))));
                $company->calc->x_iPE = $pe_effect * ($company->turn->iPE / ($this->iPE_sum + 1));
            }  

            $company->calc->dismissal_free_specialists = DismissalFreeSpecialists::formula($this, $company);
            $company->calc->hunting_specialists = HuntingSpecialists::formula($this, $company);
            $company->calc->empl_released_form_commercial_projects = EmplReleasedFormCommercialProjects::formula($this, $company);

            $v = ExecutionHiringRequest::formula($this, $company);
            $company->calc->hire_in_fact = $v[0];
            $company->calc->hire_costs = $v[1];

            $v = ExecutionFiringRequest::formula($this, $company);
            $company->calc->fire_in_fact = $v[0];
            $company->calc->fire_costs = $v[1];

            $company->calc->salary_increase = SalaryIncrease::formula($this, $company);


            $company->calc->income = Income::formula($this, $company);
            
            // Штраф за дизбалланс
            $investment_balance = 1 - 0.3 * (
                abs($company->calc->x_iHR - $company->calc->x_iSales) +
                abs($company->calc->x_iSales - $company->calc->x_iAccount) +
                abs($company->calc->x_iAccount - $company->calc->x_iPE)
            ) / 3;
            $company->calc->income *= $investment_balance;
            
            $company->calc->tax = Tax::formula($this, $company);
            $company->calc->FOT = FOT::formula($this, $company);

            $company->calc->empl_sold_by_sales = EmplSoldBySales::formula($this, $company);

            $company->calc->empl_sold_by_sales *= $investment_balance;

            $company->calc->empl_sold_by_accounts = EmplSoldByAccounts::formula($this, $company);
            $company->calc->rate_inc_by_sales = RateIncBySales::formula($this, $company);
            $company->calc->rate_inc_by_accounts = RateIncByAccounts::formula($this, $company);

            $company->nattr = new CompanyAttr();

            $company->calc->empl_notbillable_new =
                    $company->calc->empl_notbillable
                    - $company->calc->dismissal_free_specialists
                    + $company->calc->empl_released_form_commercial_projects
                    + $company->calc->hire_in_fact
                    - $company->calc->fire_in_fact
                    - $company->calc->empl_sold_by_sales
                    - $company->calc->empl_sold_by_accounts;

            $company->calc->empl_billable_new =
                    $company->calc->empl_billable
                    - $company->calc->hunting_specialists
                    - $company->calc->empl_released_form_commercial_projects
                    + $company->calc->empl_sold_by_sales
                    + $company->calc->empl_sold_by_accounts;

            if ($company->calc->empl_notbillable_new < 0) {
                $company->calc->empl_billable_new += $company->calc->empl_notbillable_new;
                $company->calc->empl_notbillable_new = 0;
            }

            $company->nattr->rate =
                    $company->attr->rate
                    + $company->calc->rate_inc_by_accounts
                    + $company->calc->rate_inc_by_sales;

            $company->nattr->empl =
                    $company->calc->empl_billable_new
                    + $company->calc->empl_notbillable_new;

            if ($company->nattr->empl != 0)
                $company->nattr->kz = $company->calc->empl_billable_new / $company->nattr->empl;

            $company->nattr->salary =
                    $company->attr->salary + $company->calc->salary_increase;

            $company->nattr->balance =
                    $company->attr->balance
                    + $company->calc->income
                    - $company->calc->tax
                    - $company->calc->FOT
                    - $company->turn->iHR
                    - $company->turn->iSales
                    - $company->turn->iAccount
                    - $company->turn->iPE
                    - $company->calc->hire_costs
                    - $company->calc->fire_costs;
        }
    }

    public function cleanUp()
    {
        $this->fire_sum = 0;
        $this->hire_sum = 0;
        $this->empl_sum = 0;
        $this->iHR_sum = 0;
        $this->iSales_sum = 0;
        $this->iAccount_sum = 0;
        $this->iPE_sum = 0;
        $this->empl_demand = 0;

        foreach ($this->companies as $key=>$company) {
            $company->attr = $company->nattr;
            $company->turn->setCompanyAttr(clone $company->attr);
            $company->nattr = null;
            $company->calc = null;

            if ($company->attr->balance<=0 or $company->attr->empl<=0) {
                unset($this->companies[$key]);
            }
        }
    }

    function toArray($prefix = ''): array
    {
        return [
            $prefix.'market_oid'=> spl_object_id($this),
            $prefix.'fire_sum' => $this->fire_sum,
            $prefix.'hire_sum' => $this->hire_sum,
            $prefix.'empl_sum' => $this->empl_sum,
            $prefix.'iHR_sum' => $this->iHR_sum,
            $prefix.'iSales_sum' => $this->iSales_sum,
            $prefix.'iAccount_sum' => $this->iAccount_sum,
            $prefix.'iPE_sum' => $this->iPE_sum,
            $prefix.'empl_demand' => $this->empl_demand,
            $prefix.'x_empl_demand' => $this->factors->x_empl_demand,
        ];
    }

}


