<?php
use \unreal4u\TelegramAPI\HttpClientRequestHandler;
use \unreal4u\TelegramAPI\TgLog;
use \unreal4u\TelegramAPI\Telegram\Methods\SendMessage;
include('vendor/autoload.php');

class TelegramHandler {

  public function sendTelegramMessage($textMessage){

    $loop = \React\Eve