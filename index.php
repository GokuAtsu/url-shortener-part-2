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
$orderHand