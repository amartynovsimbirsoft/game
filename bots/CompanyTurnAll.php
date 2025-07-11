<?php

/**
 * Базовая стратегия для теста - все деньги делим на 4 части
 */
class CompanyTurnAll extends CompanyTurn
{
    public function loadTurnData()
    {
        $v = round($this->attr->balance / 4);
        $this->iSales = $v;
        $this->iHR = $v;
        $this->iPE = $v;
        $this->iAccount = $v;
    }
}
