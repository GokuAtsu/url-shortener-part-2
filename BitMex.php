
<?php

class BitMex {

  //const API_URL = 'https://testnet.bitmex.com';
  const API_URL = 'https://www.bitmex.com';
  const API_PATH = '/api/v1/';
  const SYMBOL = 'XBTUSD';

  private $apiKey;
  private $apiSecret;

  private $ch;

  public $error;
  public $printErrors = false;
  public $errorCode;
  public $errorMessage;

  /*
   * @param string $apiKey    API Key
   * @param string $apiSecret API Secret
   */

  public function __construct($apiKey = '', $apiSecret = '') {

    $this->apiKey = $apiKey;
    $this->apiSecret = $apiSecret;

    $this->curlInit();

  }

  /*
   * Public
   */

  /*
   * Get Ticker
   *
   * @return ticker array
   */

  public function getTicker() {

    $symbol = self::SYMBOL;
    $data['function'] = "instrument";
    $data['params'] = array(
      "symbol" => $symbol
    );

    $return = $this->publicQuery($data);

    if(!$return || count($return) != 1 || !isset($return[0]['symbol'])) return false;

    $return = array(
      "symbol" => $return[0]['symbol'],
      "last" => $return[0]['lastPrice'],
      "bid" => $return[0]['bidPrice'],
      "ask" => $return[0]['askPrice'],
      "high" => $return[0]['highPrice'],
      "low" => $return[0]['lowPrice']
    );

    return $return;

  }

  /*
   * Get Candles
   *
   * Get candles history
   *
   * @param $timeFrame can be 1m 5m 1h