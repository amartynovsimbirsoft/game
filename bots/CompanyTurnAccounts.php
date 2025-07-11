<?php

/**
 * Базовая стратегия для теста - все деньги вкладываем в аккаунтов
 */
class CompanyTurnAccounts extends CompanyTurn
{
    public function loadTurnData()
    {
        $this->iAccount = $this->attr->balance;
    }
}
