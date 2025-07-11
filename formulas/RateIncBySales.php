<?php

/**
 * Повышение среднего рейта от деятельности сейлзов
 * Запускать после формулы EmplSoldBySales!
 */
class RateIncBySales
{
    public static function formula(Market $market, Company $company) : int
    {
        $x = $market->factors->x_RateIncBySales;
        $rate = $company->attr->rate;
        $empl_billable = $company->calc->empl_billable;
        $empl_sold_by_sales = $company->calc->empl_sold_by_sales;
        $empl_demand = $market->empl_demand;
        $x_iSales = $company->calc->x_iSales;

        // новый сейлзовый рейт, который выше среднего, он зависит от инвестиций в продажи и от конкуренции
        // вычисление прироста среднего рейта всей компании зависит от объема сотрудников
        // на КЗ (у них рейт не изменился после продаж)
        $sales_inc_rate = round($x * $rate * ($x_iSales + $empl_demand)/2);

        if ((($empl_billable + $empl_sold_by_sales)==0)) {
                return 0;
        }
        else {
            return round(
                ($empl_billable + $empl_sold_by_sales * $sales_inc_rate) / ($empl_billable + $empl_sold_by_sales)
            );
        }

    }
}
