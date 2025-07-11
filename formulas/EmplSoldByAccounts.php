<?php

/**
 * Продажи аккаунтов
 */
class EmplSoldByAccounts
{
    public static function formula(Market $market, Company $company) : int
    {
        $x = $market->factors->x_EmplSoldByAccounts;
        $empl_notbillable = $company->calc->empl_notbillable;
        $x_iAccount = $company->calc->x_iAccount;
        $x_iPE = $company->calc->x_iPE;
        $empl_demand = $market->empl_demand;

        return round(
            $x * $empl_notbillable * ($x_iAccount+$x_iPE+$empl_demand)/3
        );

    }
}
