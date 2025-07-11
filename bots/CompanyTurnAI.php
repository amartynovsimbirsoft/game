<?php

/**
 * Базовая стратегия для теста - делаем чуть более гибкий алгоритм
 */

class CompanyTurnAI extends CompanyTurn
{
    public function loadTurnData()
    {
        $this->hire = $this->fire = $this->iAccount = $this->iPE = $this->iHR = $this->iSales = 0;

        $bal = $this->attr->balance;
        $kz = $this->attr->kz;
        $salary = $this->attr->salary;
        $empl = $this->attr->empl;

        if ($kz > 0.7) {
            // сколько можно безопасно нанять?
            // Половина баланса / salary
            $v = round( ($bal/2) / 4) ;
            $this->hire = min( round(($bal/3)  / $salary) -1, 5) ;
            $this->iSales = $v;
            $this->iHR = $v;
            $this->iPE = $v;
            $this->iAccount = $v;
        }
        else {
            $this->iHR = round($bal/4);
            $this->iSales = round($bal/2);
            $this->iPE = round($bal/4);
            $this->fire = 3;
        }

    }
}
