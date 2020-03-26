
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
   * @param $count candles count
   * @param $offset timestamp conversion offset in seconds
   *
   * @return candles array (from past to present)
   */

  public function getCandles($timeFrame,$count,$offset = 0) {

    $symbol = self::SYMBOL;
    $data['function'] = "trade/bucketed";
    $data['params'] = array(
      "symbol" => $symbol,
      "count" => $count,
      "binSize" => $timeFrame,
      "partial" => "false",
      "reverse" => "true"
    );

    $return = $this->publicQuery($data);

    $candles = array();
	  $candleI = 0;
    // Converting
    foreach($return as $item) {

      $time = strtotime($item['timestamp']) + $offset; // Unix time stamp

      $candles[$candleI] = array(
        'timestamp' => date('Y-m-d H:i:s',$time), // Local time human-readable time stamp
        'time' => $time,
        'open' => $item['open'],
        'high' => $item['high'],
        'close' => $item['close'],
        'low' => $item['low']
      );
	    $candleI++;
    }
    return $candles;

  }

  /*
   * Get Order
   *
   * Get order by order ID
   *
   * @return array or false
   */

  public function getOrder($orderID,$count = 100) {

    $symbol = self::SYMBOL;
    $data['method'] = "GET";
    $data['function'] = "order";
    $data['params'] = array(
      "symbol" => $symbol,
      "count" => $count,
      "reverse" => "true"
    );

    $orders = $this->authQuery($data);

    foreach($orders as $order) {
      if($order['orderID'] == $orderID) {
        return $order;
      }
    }

    return false;

  }


  /*
   * Get Orders
   *
   * Get last 100 orders
   *