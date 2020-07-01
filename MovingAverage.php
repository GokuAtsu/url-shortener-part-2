
<?php


class MovingAverage {

  private $PERIODI;
  private $CANDLES;

  public function isPositive(){
    $resMedia = $this->getLastTwoValues();
    if((floatval($resMedia[0]) - floatval($resMedia[1])) > 0){
      return true;
    }
    return false;
  }
  

  public function getLastValue() {
    
    $candele = $this->CANDLES;

    $somma = 0; 

    for($i=0; $i<($this->PERIODI); $i++){
      $somma += $candele[$i]['close'];


    }

    return floatval($somma/$this->PERIODI);

  }

  public function getLastTwoValues() {

    $candele = $this->CANDLES;