<?php
include "BitMex.php";
include "MovingAverage.php";
include "OrderHandler.php";
include "TelegramHandler.php";
include "Donchian.php";
include "WMA.php";
include "DBHandler.php";
include "Logger.php";
include "Validator.php";

//INSERT BITMEX API KEY HERE
$key = "";
$secret = "";

$PERIODI = 16;
$LEVA = 1;

$bitmex = new BitMex($key, $secret);
$MA = new MovingAverage($bitmex);
$orderHandler = new OrderHandler($bitmex);
$donchian = new Donchian($bitmex);
$WMA = new WMA($bitmex);
$telegramHandler = new TelegramHandler();
$DBHandler = new DBHandler();

$tick = $bitmex->getTicker();
$bandWidth = $donchian->getBandWidth();

//INIT CONTRACTS
$saldo = floatval($bitmex->getWallet()['amount']) / 100000000;
$contrattiInvestiti = 0;
$posizioniAperte = $bitmex->getOpenPositions();

if (count($posizioniAperte) > 0) {
    $contrattiInvestiti = intval($posizioniAperte[0]['currentQty']) > 0 ? intval($posizioniAperte[0]['currentQty']) : -(intval($posizioniAperte[0]['currentQty']));
}

$contrattiAcq = (round(floatval($saldo) * floatval($tick['last']) * 0.9 * ($LEVA)) - $contrattiInvestiti);
$contrattiAcqAll = round(floatval($saldo) * floatval($tick['last']) * 0.9 * ($LEVA));
$fixedContracts = 30;
$prezzoAcq = intval($tick['last']);
$step = (int) $DBHandler->getField("numero_operazioni");
$verso = (int) $DBHandler->getField("verso");
$bandaH = $donchian->getBandaH();
$bandaL = $donchian->getBandaL();

$logger = new Logger($tick['last'], $bandWidth, $bandaH, $bandaL);
$validator = new Validator($tick, $posizioniAperte);

if (!isset($tick) || !isset($saldo) || !isset($bandaH) || !isset($bandaL) || !isset($bandWidth)) {
    $logger->logData();
    $telegramHandler->sendTelegramMessage("Validator success: chiamata a Bitmex 