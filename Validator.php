<?php


class Validator {

  private $currentPrice;
  private $posizioniAperte;

  public function __construct($currentPrice, $posizioniAperte) {

    $this->currentPrice = $c