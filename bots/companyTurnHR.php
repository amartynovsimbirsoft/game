<?php
/**
 * Базовая стратегия для теста - все деньги вкладываем в HR
 */
class CompanyTurnHR extends CompanyTurn
{
    public function loadTurnData()
    {
        $this->iHR = $this->attr->balance;
    }
}
