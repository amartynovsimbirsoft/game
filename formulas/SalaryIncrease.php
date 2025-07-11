<?php

/**
 * Расчет прироста средней зарплаты для компании
 *
 */
class SalaryIncrease
{

    /**
     * @return int Возвращается прирост к средней зарплате компании (прирост к salary)
     */
    public static function formula(Market $market, Company $company) : int
    {
        // настроечный коэффициент
        $x = $market->factors->x_SalaryIncrease;
        $salary = $company->attr->salary;
        $x_iHR = $company->calc->x_iHR;
        $empl_demand = $market->empl_demand;

        return round(
            $x * $salary * $empl_demand * (1-$x_iHR)
        );
    }
}
