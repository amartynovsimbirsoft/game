<?php

/**
 * Это вычислимые атрибуты компании, которые вычисляются Рынком (Market)
 * в зависимости как от параметров самого Рынка, так и действий и состояний
 * других компаний-игроков
 */
class companyCalc
{
    public int $empl_billable = 0;
    public int $empl_notbillable = 0;

    public float $x_iHR = 0;
    public float $x_iSales = 0;
    public float $x_iAccount = 0;
    public float $x_iPE = 0;

    public int $dismissal_free_specialists = 0;
    public int $hunting_specialists = 0;
    public int $empl_released_form_commercial_projects = 0;
    public int $hire_in_fact = 0;
    public int $hire_costs = 0;
    public int $fire_in_fact = 0;
    public int $fire_costs = 0;
    public int $salary_increase = 0;
    public int $income = 0;
    public int $tax = 0;
    public int $FOT = 0;
    public int $empl_sold_by_accounts = 0;
    public int $empl_sold_by_sales = 0;
    public int $rate_inc_by_sales = 0;
    public int $rate_inc_by_accounts = 0;
    public int $empl_notbillable_new = 0;
    public int $empl_billable_new = 0;

    function toArray($prefix = ''): array
    {
        return [
            $prefix.'calc_oid'=> spl_object_id($this),
            $prefix.'empl_billable' => $this->empl_billable,
            $prefix.'empl_notbillable' => $this->empl_notbillable,
            $prefix.'x_iHR' => $this->x_iHR,
            $prefix.'x_iSales' => $this->x_iSales,
            $prefix.'x_iAccount' => $this->x_iAccount,
            $prefix.'x_iPE' => $this->x_iPE,
            $prefix.'dismissal_free_specialists' => $this->dismissal_free_specialists,
            $prefix.'hunting_specialists' => $this->hunting_specialists,
            $prefix.'empl_released_form_commercial_projects' => $this->empl_released_form_commercial_projects,
            $prefix.'hire_in_fact' => $this->hire_in_fact,
            $prefix.'hire_costs' => $this->hire_costs,
            $prefix.'fire_in_fact' => $this->fire_in_fact,
            $prefix.'fire_costs' => $this->fire_costs,
            $prefix.'salary_increase' => $this->salary_increase,
            $prefix.'income' => $this->income,
            $prefix.'tax' => $this->tax,
            $prefix.'FOT' => $this->FOT,
            $prefix.'empl_sold_by_sales' => $this->empl_sold_by_sales,
            $prefix.'empl_sold_by_accounts' => $this->empl_sold_by_accounts,
            $prefix.'rate_inc_by_sales' => $this->rate_inc_by_sales,
            $prefix.'rate_inc_by_accounts' => $this->rate_inc_by_accounts,
            $prefix.'empl_notbillable_new' => $this->empl_notbillable_new,
            $prefix.'empl_billable_new' => $this->empl_billable_new,
        ];
    }
}
