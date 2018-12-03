<?php

use Medoo\Medoo;

class Arena{
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

  function getEnemy(){
    $level = $this -> hero -> getLevel();

    $enemy = $this -> database -> rand("hero", "id", [
    	"level[<>]" => [$level - 5, $level + 5]
    ]);

    print_r($enemy);
    return $enemy;
  }
}
