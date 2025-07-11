<?php

/**
 * Повышение среднего рейта от деятельности аккаунтов
 * Запускать после формулы EmplSoldByAccounts!
 */
class RateIncByAccounts
{
    public static function formula(Market $market, Company $company) : int
    {
        $x = $market->factors->x_RateIncByAccounts;
        $rate = $company->attr->rate;
        $empl_billable = $company->calc->empl_billable;
        $empl_sold_by_accounts = $company->calc->empl_sold_by_accounts;
        $empl_demand = $market->empl_demand;
        $x_iAccount = $company->calc->x_iAccount;

        $accounts_inc_rate = round($x * $rate * ($x_iAccount + $empl_demand)/2);

        if ((($empl_billable + $empl_sold_by_accounts)==0)) {
                return 0;
        }
        else {
            return round(
                ($empl_billable + $empl_sold_by_accounts * $accounts_inc_rate) / ($empl_billable + $empl_sold_by_accounts),
            );
        }

    }
}
