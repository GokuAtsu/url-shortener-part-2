<?php


class EMA {

  private $PERIODI;
  private $CANDLES;

  public function isPositive(){
    $arrayEMA = $this->exponentialMovingAverage();

    if($arrayEMA[0] > $arrayEMA[1]){
