<?php

/**
 * Настроечные коэффициенты формул
 */
class Factors
{
    public float $x_empl_demand = 0.1; // это суммирующий коэффициет
    public float $x_DismissalFreeSpecialists = 1;
    public float $x_HuntingSpecialists = 1;
    public float $x_EmplReleasedFormCommercialProjects = 0.05;
    public float $x_ExecutionHiringRequest = 1;
    public float $x_ExecutionFiringRequest = 1;
    public float $x_SalaryIncrease = 1;
    public float $x_Income = 1;
    public int $hours_in_month = 150;
    public float $x_Tax = 1;
    public float $tax_USN = 0.05; // УСН (упрощенка)
    public float $tax_OSN = 0.2; // ОСН (ндс)
    public float $tax_usn_income_limit = 100000000; // когда переход на ндс
    public float $x_FOT = 1;
    public float $x_EmplSoldBySales = 1;
    public float $x_EmplSoldByAccounts = 1;
    public float $x_RateIncBySales = 1;
    public float $x_RateIncByAccounts = 1;
}
