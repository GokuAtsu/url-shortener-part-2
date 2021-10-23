<?php


class WMA {

  private $PERIODI = 80;
  private $TIMEFRAME = "1h";
  private $bitmex;

  public function isPositive(){
    $resMedia = $this->getLastTwoValues();
    if((floatval($resMedia