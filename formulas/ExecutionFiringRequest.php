<?php

/**
 * Исполнение заявки на увольнение от компании
 * Заявка исполняется с некоторой вероятностью менее 100% (в общем случае)
 * Стоимость увольнения - полная заявка умноженная на salary
 *
 * на 29 06 2025 формула абсолютно идентична формуле найма
 */
class ExecutionFiringRequest
{

    /**
     * @return array [$FireInFact, $FireCosts]
     */
    public static function formula(Market $market, Company $company) : array
    {
        // настроечный коэффициент
        $x = $market->factors->x_ExecutionFiringRequest;

        // Заявка на увольнение
        $order_to_fire = $company->turn->fire;

        // коэффициент спроса на кадры - это процент на сколько хочет вырасти рынок в кол-ве специалистов
        // чем ниже - тем сложнее увольнять
        $empl_demand = $market->empl_demand;

        // нормализованный [0..1] коэффициент инвестиций в HR
        // чем выше - тем легче увольнять
        $x_iHR = $company->calc->x_iHR;

        $min = round($x_iHR*1000);
        $max = $min + round((1000-$empl_demand*1000)/4);
        if ($max > 1000) {
            $max = 1000;
        }
        $k = rand($min, $max) / 1000;
        $FireInFact = round($order_to_fire * $k);
        $FireCosts = $order_to_fire * $company->attr->salary;

        return [$FireInFact, $FireCosts];
    }
}
