<?php

/**
 * Продажи сейлзов
 */
class EmplSoldBySales
{
    public static function formula(Market $market, Company $company) : int
    {
        $x = $market->factors->x_EmplSoldBySales;
        $empl_notbillable = $company->calc->empl_notbillable;
        $x_iSales = $company->calc->x_iSales;
        $x_iPE = $company->calc->x_iPE;
        $empl_demand = $market->empl_demand;

        return round(
            $x * $empl_notbillable * ($x_iSales+$x_iPE+$empl_demand)/3
        );

    }
}
