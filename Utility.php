<?php

class Utility {


  public function __construct() {

    

  }

  public function threeCandlesLong($periodi, $candles){
    $subArray = array();
    $tempArray = $candles;
    $firstPositive = "-1";

    for ($i = 0; $i < count($candles); $i++) {
        $tmp_MA = new EMA($periodi, $tempArray);

        if (!($tmp_MA->isPositive())) {
            if ($firstPositive == "-1") {
                $firstPositive = "0";
            } else if ($firstPositive == "1") {
                break;
            }
            array_push(