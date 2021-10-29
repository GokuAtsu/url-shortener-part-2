<?php


class WMA {

  private $PERIODI = 80;
  private $TIMEFRAME = "1h";
  private $bitmex;

  public function isPositive(){
    $resMedia = $this->getLastTwoValues();
    if((floatval($resMedia[0]) - floatval($resMedia[1])) > 0){
      return true;
    }
    return false;
  }
  

  public function getLastValue() {

    
    $candele = $this->bitmex->getCandles($this->TIMEFRAME, $this->PERIODI);

    $numCandele = count($candele);

   