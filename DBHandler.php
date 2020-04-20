<?php


class DBHandler {

  private $conn = null;
 

  public function __construct() {

    $this->conn = new mysqli("localhost", "root", "", "test");

  }

  function getField($field) {
    $sql = "select " .$field ." from posizione_aperta w