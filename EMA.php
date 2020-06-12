<?php


class EMA {

  private $PERIODI;
  private $CANDLES;

  public function isPositive(){
    $arrayEMA = $this->exponentialMovingAverage();

    if($arrayEMA[0] > $arrayEMA[1]){
      return true;
    }
    return false;
  }

  public function getLastValue(){
    $arrayEMA = $this->exponentialMovingAverage();
    return $arrayEMA[0];
  }

  public function exponentialMovingAverage(): array
  {
    $numbers = $this->CANDLES;
    $n = $th