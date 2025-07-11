<?php

/**
 * Это атрибуты компании, которые видит игрок
 * Данные атрибуты описывают состояние компании перед ходом
 */
class companyAttr
{
    public int $empl = 0; // количество сотрудников в компании
    public float $kz = 0; // коммерческая занятость
    public int $rate = 0; // средний рейт за час
    public int $salary = 0; // средняя зарплата
    public int $balance = 0; // остаток средств на расчетном счете

    function __construct($empl = 0, $kz = 0, $rate = 0, $salary = 0, $balance = 0) {
        $this->empl = $empl;
        $this->kz = $kz;
        $this->rate = $rate;
        $this->salary = $salary;
        $this->balance = $balance;
    }

    function toArray($prefix = ''): array
    {
        return [
            $prefix.'attr_oid'=> spl_object_id($this),
            $prefix.'empl' => $this->empl,
            $prefix.'kz' => $this->kz,
            $prefix.'rate' => $this->rate,
            $prefix.'salary' => $this->salary,
            $prefix.'balance' => $this->balance,
        ];
    }
}
