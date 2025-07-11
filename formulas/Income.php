<?php

/**
 * Вычисление дохода компании
 */
class Income
{

    /**
     * @return int Размер дохода
     */
    public static function formula(Market $market, Company $company) : int
    {
        // настроечный коэффициент
        $x = $market->factors->x_Income;

        $hours = $market->factors->hours_in_month;

        $kz = $company->attr->kz;
        $empl = $company->attr->empl;
        $rate = $company->attr->rate;

        return round(
            $x * $kz * $empl * $rate * $hours
        );
    }
}
