<?php

use Medoo\Medoo;

class Dungeon{
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

  function getEnemyId(){
    return $this -> database -> get('heroes', 'dungeon', [
      'id' =>  $this -> hero -> id,
    ]);
  }
}
