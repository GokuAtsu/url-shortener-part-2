
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
   * @return orders array (from the past to the present)
   */

  public function getOrders($count = 100) {

    $symbol = self::SYMBOL;
    $data['method'] = "GET";
    $data['function'] = "order";
    $data['params'] = array(
      "symbol" => $symbol,
      "count" => $count,
      "reverse" => "true"
    );

    return array_reverse($this->authQuery($data));
  }

  /*
   * Get Open Orders
   *
   * Get open orders from the last 100 orders
   *
   * @return open orders array
   */

  public function getOpenOrders() {

    $symbol = self::SYMBOL;
    $data['method'] = "GET";
    $data['function'] = "order";
    $data['params'] = array(
      "symbol" => $symbol,
      "reverse" => "true"
    );

    $orders = $this->authQuery($data);

    $openOrders = array();
    foreach($orders as $order) {
      if($order['ordStatus'] == 'New' || $order['ordStatus'] == 'PartiallyFilled') $openOrders[] = $order;
    }

    return $openOrders;

  }

  /*
   * Get Open Positions
   *
   * Get all your open positions
   *
   * @return open positions array
   */

  public function getOpenPositions() {

    $symbol = self::SYMBOL;
    $data['method'] = "GET";
    $data['function'] = "position";
    $data['params'] = array(
      "symbol" => $symbol
    );

    $positions = $this->authQuery($data);

    $openPositions = array();
    foreach($positions as $position) {
      if(isset($position['isOpen']) && $position['isOpen'] == true) {
        $openPositions[] = $position;
      }
    }

    return $openPositions;
  }

  /*
   * Close Position
   *
   * Close open position
   *
   * @return array
   */

  public function closePosition($price) {

    $symbol = self::SYMBOL;
    $data['method'] = "POST";
    $data['function'] = "order/closePosition";
    $data['params'] = array(
      "symbol" => $symbol,
      "price" => $price
    );

    return $this->authQuery($data);
  }

  /*
   * Edit Order Price
   *
   * Edit you open order price
   *
   * @param $orderID    Order ID
   * @param $price      new price
   *
   * @return new order array
   */

  public function editOrderPrice($orderID,$price) {

    $data['method'] = "PUT";
    $data['function'] = "order";
    $data['params'] = array(
      "orderID" => $orderID,
      "price" => $price
    );

    return $this->authQuery($data);
  }

  /*
   * Create Order
   *
   * Create new market order
   *
   * @param $type can be "Limit"
   * @param $side can be "Buy" or "Sell"
   * @param $price BTC price in USD
   * @param $quantity should be in USD (number of contracts)
   * @param $maker forces platform to complete your order as a 'maker' only
   *
   * @return new order array
   */

  public function createOrder($type,$side,$price,$quantity,$maker = false) {

    $symbol = self::SYMBOL;
    $data['method'] = "POST";
    $data['function'] = "order";
    $data['params'] = array(
      "symbol" => $symbol,
      "side" => $side,
      "price" => $price,
      "orderQty" => $quantity,
      "ordType" => $type
    );

    if($maker) {
      $data['params']['execInst'] = "ParticipateDoNotInitiate";
    }

    return $this->authQuery($data);
  }

  /*
   * Cancel All Open Orders
   *
   * Cancels all of your open orders
   *
   * @param $text is a note to all closed orders
   *
   * @return all closed orders arrays
   */

  public function cancelAllOpenOrders($text = "") {

    $symbol = self::SYMBOL;
    $data['method'] = "DELETE";
    $data['function'] = "order/all";
    $data['params'] = array(
      "symbol" => $symbol,
      "text" => $text
    );

    return $this->authQuery($data);
  }

  /*
   * Get Wallet
   *
   * Get your account wallet
   *
   * @return array
   */

  public function getWallet() {

    $data['method'] = "GET";
    $data['function'] = "user/wallet";
    $data['params'] = array(
      "currency" => "XBt"
    );

    return $this->authQuery($data);
  }

  /*
   * Get Margin
   *
   * Get your account margin
   *
   * @return array
   */

  public function getMargin() {

    $data['method'] = "GET";
    $data['function'] = "user/margin";
    $data['params'] = array(
      "currency" => "XBt"
    );

    return $this->authQuery($data);
  }

  /*
   * Get Order Book
   *
   * Get L2 Order Book
   *
   * @return array
   */

  public function getOrderBook($depth = 25) {

    $symbol = self::SYMBOL;
    $data['method'] = "GET";
    $data['function'] = "orderBook/L2";
    $data['params'] = array(
      "symbol" => $symbol,
      "depth" => $depth
    );

    return $this->authQuery($data);
  }

  /*
   * Set Leverage
   *
   * Set position leverage
   * $leverage = 0 for cross margin
   *
   * @return array
   */

  public function setLeverage($leverage) {