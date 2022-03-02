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
$posizioniAperte = $bitmex->