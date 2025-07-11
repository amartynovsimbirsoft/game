<?php

/**
 * Вычисление ФОТ
 */
class FOT
{

    /**
     * @return int Размер ФОТ на текущем ходу
     */
    public static function formula(Market $market, Company $company) : int
    {
        $x = $market->factors->x_FOT;
        $empl = $company->attr->empl;
        $salary = $company->attr->salary;

        return round(
            $x * $empl * $salary
        );
    }
}
