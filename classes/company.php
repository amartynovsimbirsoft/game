<?php

class Company
{
    public $name;

    /** @var companyAttr
     * атрибуты компании ДО обсчета хода (текущие перед началом хода)
     */
    public CompanyAttr $attr;

    /** @var CompanyTurn|null
     * параметры хода компании
     * объект может являться ботом для реализации своей логики игры
     * для этого ему нужно знать состояние компании, но он не может его изменять
     * для этого в объект CompanyMove в момент его создания передается
     * копия атрибутов CompanyAttr в начале хода
     */
    public CompanyTurn $turn;

    /** @var CompanyAttr|null
     * $nattr это новый атрибуты компании (состояние компании) после обсчета хода
     * сохраняются в историю под индексом хода
     * $attr это параметры ДО обсчета хода
     * $nattr это параметры ПОСЛЕ обсчета хода
     * после сохранения в историю, $nattr копируется в $attr и обнуляется ($nattr=null)
     * таким образом, на следующий ход компания имеет атрибуты nattr в поле attr как начальные
     * нового для хода
     */
    public ?CompanyAttr $nattr;

    /** @var CompanyCalc|null
     * Это объект, который содержит набор вычислимых полей компании, которые вычисляются
     * Рынком и зависят как от параметров Рынка, так и от суммарных действий других игроков.
     * Данные вычислимые поля являются промежуточным этапом в вычислении нового состояния компании.
     */
    public ?CompanyCalc $calc;

    public function __construct($name, companyAttr $attr, CompanyTurn $turn)
    {
        $this->name = $name;
        $this->attr = $attr;
        $this->turn = $turn;
        $this->nattr = null;
    }

    public function __clone()
    {
        $this->attr = clone $this->attr;
        if (is_object($this->turn)) {
            $this->turn = clone $this->turn;
        }
        if (is_object($this->nattr)) {
            $this->nattr = clone $this->nattr;
        }
        if (is_object($this->calc)) {
            $this->calc = clone $this->calc;
        }
    }

    public function toArray(): array
    {
        $r = ['company_oid'=> spl_object_id($this), 'name' => $this->name];
        if (is_object($this->attr)) {
            $r = array_merge($r, $this->attr->toArray('a_'));
        }
        if (is_object($this->turn)) {
            $r = array_merge($r, $this->turn->toArray('t_'));
        }
        if (is_object($this->calc)) {
            $r = array_merge($r, $this->calc->toArray('c_'));
        }
        if (is_object($this->nattr)) {
            $r = array_merge($r, $this->nattr->toArray('n_'));
        }
        return $r;
    }

}
