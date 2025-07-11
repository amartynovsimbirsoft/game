<?php
include 'classes/game.php';
include 'classes/market.php';
include 'classes/company.php';
include 'classes/companyAttr.php';
include 'classes/companyTurn.php';
include 'classes/companyCalc.php';
include 'classes/scenario.php';
include 'classes/factors.php';
include 'bots/companyTurnHR.php';
include 'bots/CompanyTurnSales.php';
include 'bots/CompanyTurnAll.php';
include 'bots/CompanyTurnPE.php';
include 'bots/CompanyTurnAccounts.php';
include 'bots/CompanyTurnAI.php';
include 'classes/logger.php';
include 'formulas/DismissalFreeSpecialists.php';
include 'formulas/HuntingSpecialists.php';
include 'formulas/EmplReleasedFormCommercialProjects.php';
include 'formulas/ExecutionHiringRequest.php';
include 'formulas/ExecutionFiringRequest.php';
include 'formulas/SalaryIncrease.php';
include 'formulas/Income.php';
include 'formulas/Tax.php';
include 'formulas/FOT.php';
include 'formulas/EmplSoldBySales.php';
include 'formulas/EmplSoldByAccounts.php';
include 'formulas/RateIncBySales.php';
include 'formulas/RateIncByAccounts.php';
include 'conf/Conf.php';

$g = new Game();
$g->main();

