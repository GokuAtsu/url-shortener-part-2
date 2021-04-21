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

            array_push($subArray, $candles[$i]);

        }

        array_shift($tempArray);
    }

    $candeleP = 1;
    for ($i = 0; $i < (count($subArray)-1); $i++) {
        if ($subArray[$i]['close'] < $subArray[$i]['open'] && $subArray[$i]['close'] < $subArray[$i+1]['close']) {
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

  public function setupLong($AMA8, $AMA13, $AMA21){
    $val8 = $AMA8->getLastValue();
    $val13 = $AMA13->getLastValue();
    $val21 = $AMA21->getLastValue();

    if(($val13/$val8) < 0.9975 && ($val21/$val13) < 0.9975){
      return true;
    }
    return false;
  }

  public function setupShort($AMA8, $AMA13, $AMA21){
    $val8 = $AMA8->getLastValue();
    $val13 = $AMA13->getLastValue();
    $val21 = $AMA21->getLastValue(