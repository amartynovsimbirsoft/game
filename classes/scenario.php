<?php

class Scenario
{
    public function run(Market $market, $turnNumber)
    {
        //$market->factors->x_empl_demand = 1;
        if ($turnNumber == 1) {
            $state = new CompanyAttr(empl:0,kz:0,rate:0,salary:0,balance:0);
            $turn = new CompanyTurn(clone $state);
            $market->addCompany(new Company('Zero', $state, $turn));

            $state = new CompanyAttr(empl:101,kz:0.7,rate:3000,salary:180000,balance:0);
            $turn = new CompanyTurnHR(clone $state);
            $market->addCompany(new Company('HR', $state, $turn));

            $state = new CompanyAttr(empl:102,kz:0.7,rate:3000,salary:180000,balance:0);
            $turn = new CompanyTurnAll(clone $state);
            $market->addCompany(new Company('ALL', $state, $turn));

            $state = new CompanyAttr(empl:103,kz:0.7,rate:3000,salary:180000,balance:0);
            $turn = new CompanyTurnSales(clone $state);
            $market->addCompany(new Company('Sales', $state, $turn));

            $state = new CompanyAttr(empl:104,kz:0.7,rate:3000,salary:180000,balance:0);
            $turn = new CompanyTurnAccounts(clone $state);
            $market->addCompany(new Company('Accounts', $state, $turn));

            $state = new CompanyAttr(empl:105,kz:0.7,rate:3000,salary:180000,balance:0);
            $turn = new CompanyTurnPE(clone $state);
            $market->addCompany(new Company(
                'PE',
                new CompanyAttr(empl:105,kz:0.7,rate:3000,salary:180000,balance:0),
                new CompanyTurnPE(clone $state)
            ));

            $market->addCompany(new Company(
                'AI',
                new CompanyAttr(empl:105,kz:0.7,rate:3000,salary:180000,balance:0),
                new CompanyTurnAI(clone $state)
            ));
        }
    }
}
