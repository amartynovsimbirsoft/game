<?php

/**
 * Базовая стратегия для теста - все деньги вкладываем в эффективность производства
 */
class CompanyTurnPE extends CompanyTurn
{
    public function loadTurnData()
    {
        $this->iPE = $this->attr->balance;
    }
}
