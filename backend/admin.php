<?php

use Medoo\Medoo;

class Admin{
  function __construct($hero){
    $this -> database = new Medoo([
      'database_type' => 'mysql',
      'database_name' => 'game',
      'server' => 'localhost',
      'username' => 'root',
      'password' => '',
      "charset" => "utf8",
    ]);

    $this -> hero = $hero;
  }

  function attrib($atr, $sign){
    $this -> database -> update('heroes', [
      $atr.'['.$sign.']' => 1,
    ],[
      'id' => $this -> hero -> id,
    ]);
  }
}
