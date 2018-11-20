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
    $dungeon = $this -> database -> get('heroes', [
      'dungeon',
      'dungeon_stage',
      ], [
      'id' =>  $this -> hero -> id,
    ]);

    $stage = 'stage_'.$dungeon['dungeon_stage'];

    $monster = $this -> database -> get('dungeons', $stage, [
      'id' => $dungeon['dungeon'],
    ]);

    return $this -> database -> get('monsters', 'id', [
      'name' => $monster,
    ]);
  }

  function getDungeon(){
    return $this -> database -> get('heroes', 'dungeon', [
      'id' =>  $this -> hero -> id,
    ]);
  }
}
