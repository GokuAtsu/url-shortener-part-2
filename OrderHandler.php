<?php


class OrderHandler {

  private $bitmex;

  public function __construct($bitmex) {

    $this->bitmex = $bitmex;

  }

  public function isShort(){
    $posizioniAperte = $this->bitmex->getOpenPositions();

    if(count($