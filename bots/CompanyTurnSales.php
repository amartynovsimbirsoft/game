<?php

/**
 * Базовая стратегия для теста - все деньги вкладываем в продажи
 */
class CompanyTurnSales extends CompanyTurn
{
    public function loadTurnData()
    {
        $this->iSales = $this->attr->balance;
    }
}
