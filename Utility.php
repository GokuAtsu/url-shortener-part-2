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
            array_push($subArray, $candles[$i]);

        } else {
            if ($firstPositive == "-1") {
                $firstPositive = "1";
            } else if ($firstPositive == "0") {
                break;
            }

            array_push($subArray, $candles[$i]);

        }

        array_shift($tempArray);
    }

    $candeleP = 1;
    for ($i = 0; $i < (count($subArray)-1); $i++) {  
      if ($subArray[$i]['close'] > $subArray[$i]['open'] && $subArray[$i]['close'] > $subArray[$i+1]['close']) {
            $candeleP += 1;
        } else {
            $candeleP = 1;
        }

        if ($candeleP == 3) {
            return true;
        }
    }

    return false;
  }

  public function threeCandlesShort($periodi, $candles){
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
            array_push($subArray, $candles[$i]);

        } else {
            if ($firstPositive == "-1") {
                $firstPositive = "1";
            } else if ($firstPositive == "0") {
                break;
            }

            array_push($subArray, $c