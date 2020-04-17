<?php


class DBHandler {

  private $conn = null;
 

  public function __construct() {

    $this->conn = new mysqli("localhost", "root", "", "test");

  }

  