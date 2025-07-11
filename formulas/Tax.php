<?php

/**
 * Вычисление налога компании
 * Запускать после выполнения формулы Income!
 */
class Tax
{

    /**
     * @return int Размер налога
     */
    public static function formula(Market $market, Company $company) : int
    {
        $x = $market->factors->x_Tax;
        $income = $company->calc->income;

        $tax = $market->factors->tax_USN;
        if ($income >= $market->factors->tax_usn_income_limit) {
            $tax = $market->factors->tax_OSN;
        }

        return round(
            $x * $income * $tax
        );
    }
}
