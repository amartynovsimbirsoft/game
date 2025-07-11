<?php

class CompanyTurn
{
    /** @var CompanyAttr
     * Копия атрибутов компании (состояния компании) для реализации
     * логики принятия решения, ход который рассчитывается
     */
    public CompanyAttr $attr;

    public int $fire = 0;
    public int $hire = 0;
    public int $iHR = 0;
    public int $iSales = 0;
    public int $iAccount = 0;
    public int $iPE = 0;

    public function __construct(CompanyAttr $attr)
    {
        $this->attr = $attr;
    }

    /**
     * Метод нужен для того, чтобы обновить параметры компании после завершения обсчета хода
     * Так как в этом объекте хратится только лишь клон параметров кампании
     */
    public function setCompanyAttr(CompanyAttr $attr)
    {
        $this->attr = $attr;
    }

    public function __clone()
    {
        $this->attr = clone $this->attr;
    }

     /**
      * нужно каждый раз инициализировать значения каждого аттрибута хода
      * так как объект сохраняет свое состояние
      * например, если будет такое условие
      * if (..) {
      *     $this->fire = 3;
      * } else {
      *     $this->hire = 3;
      * }
      * То результатом может быть оба:
      *     fire = 3
      *     hire = 3
      * Так как на одно ходу была истина и присвоилось fire=3, потом ложь
      * и присвоилось hire=3, то на следующем ходу оба эти значения пойдут в работу
      * так как сохранились в состоянии
      */
    public function loadTurnData()
    {
        return true;
    }

    function toArray($prefix = ''): array
    {
        return [
            $prefix.'turn_oid'=> spl_object_id($this),
            $prefix.'fire' => $this->fire,
            $prefix.'hire' => $this->hire,
            $prefix.'iHR' => $this->iHR,
            $prefix.'iSales' => $this->iSales,
            $prefix.'iAccount' => $this->iAccount,
            $prefix.'iPE' => $this->iPE,
        ];
    }
}