<?php


class Donchian {

  private $PERIODI = 16;
  private $TIMEFRAME = "1h";
  private $bitmex;

  public function __construct($bitmex) {

    $this->bitmex = $bitmex;

  }

  public function getBandaH(){
    $candele