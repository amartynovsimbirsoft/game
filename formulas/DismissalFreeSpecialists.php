<?php

/**
 * Класс реализует формулу увольнения свободных специалистов, или "увольнения с бенча" НЕ по решению компании.
 * В реальном мире, это могут быть как сокращения или увольнения по решению компании на испытательном сроке,
 * так и личное желание сотрудника уйти если долго у него не было проектов.
 * В любом случае, это случайные потери компании или ошибки найма, не запланированные увольнения.
 */
class DismissalFreeSpecialists
{
    public static function formula(Market $market, Company $company) : int
    {
        // настроечный коэффициент
        $x = $market->factors->x_DismissalFreeSpecialists;

        // количество свободных специалистов в компании
        // чем их меньше, тем меньше будут уходить
        $empl_notbillable = $company->calc->empl_notbillable;

        // коэффициент спроса на кадры - это процент на сколько хочет вырасти рынок в кол-ве специалистов
        // чем выше - тем больше уходят
        $empl_demand = $market->empl_demand;

        // нормализованный [0..1] коэффициент инвестиций в HR
        // чем выше - тем меньше будут уходить
        $x_iHR = $company->calc->x_iHR;

        // нормализованный [0..1] коэффициент инвестиций в эффективность производства
        // чем выше - тем меньше будут уходить
        $x_iPE = $company->calc->x_iPE;

        return round(
            $x * $empl_notbillable * $empl_demand * (1-$x_iHR) * (1-$x_iPE)
        );
    }
}
