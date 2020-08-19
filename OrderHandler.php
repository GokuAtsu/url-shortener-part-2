<?php


class OrderHandler {

  private $bitmex;

  public function __construct($bitmex) {

    $this->bitmex = $bitmex;

  }

  public function isShort(){
    $posizioniAperte = $this->bitmex->getOpenPositions();

    if(count($posizioniAperte) > 0 && intval($posizioniAperte[0]['currentQty']) < 0){
      return true;

    }
    return false;
  }

  public function isLong(){
    $posizioniAperte = $this->bitmex->getOpenPositions();

    if(count($posizioniAperte) > 0 && intval($posizioniAperte[0]['currentQty']) > 0){
      return true;

    }
    return false;

  }

  public function noPositions(){
    $posizioniAperte = $this->bitmex->getOpenPositions();

    if(count($posizioniAperte) > 0){
      return false;

    }
    return true;

  }

  public function noOrders(){
    $ordiniAperti = $this->bitmex->getOpenOrders();

    if(count($ordiniAperti) > 0){
      return false;

    }
    return true;

  }

  public function isPartiallyFilled(){
    $posizioniAperte = $this->bitmex->getOpenPositions();
    $ordiniAperti = $this->bitmex->getOpenOrders();

    if(count($posizioniAperte)>0 && count($ordiniAperti)> 0 ){
      return true;
    
    }

    return false;

  }

  public function openLong($prezzoAcq, $contrattiAcq, $leva){
    $this->bi