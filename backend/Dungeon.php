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
    $dungeon = $this -> database -> get('heroes', 'dungeon', [
      'id' =>  $this -> hero -> id,
    ]);

    if($dungeon % 10 == 0) $stage = 'stage_10';
    else $stage = 'stage_'.$dungeon % 10;

    $dungeon = ceil($dungeon / 10);

    $monster = $this -> database -> get('dungeons', $stage, [
      'id' => $dungeon,
    ]);

    return $this -> database -> get('monsters', 'id', [
      'name' => $monster,
    ]);
  }

  function getDungeon(){
    $dungeon = $this -> database -> get('heroes', 'dungeon', [
      'id' =>  $this -> hero -> id,
    ]);
    return ceil($dungeon / 10);
  }
}
