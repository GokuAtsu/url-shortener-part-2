<?php


class EMA {

  private $PERIODI;
  private $CANDLES;

  public function isPositive(){
    $arrayEMA = $this->exponent