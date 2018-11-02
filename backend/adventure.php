<?php

use Medoo\Medoo;

class Adventure{
  function __construct(){
    $this -> database = new Medoo([
      'database_type' => 'mysql',
      'database_name' => 'game',
      'server' => 'localhost',
      'username' => 'root',
      'password' => '',
      "charset" => "utf8",
    ]);
  }

  function getAdventures($hero){
    $adventures = $this -> database -> get('heroes', [
      'adventures',
      'adventure_reward',
      'adventure_duration',
    ], [
      'id' => $hero -> id,
    ]);

    if($adventures == NULL){
      $this -> changeAdventures($hero);
      $adventures = $this -> database -> get('heroes', [
        'adventures',
        'adventure_reward',
        'adventure_duration',
      ], [
  			'id' => $hero -> id,
  		]);
    }

    $adventure = explode("/", $adventures['adventures']);
    $reward = explode("/", $adventures['adventure_reward']);
    $duration = explode("/", $adventures['adventure_duration']);

    $adventures = $this -> database -> select('adventures', [
      'id',
      'name',
      'description',
    ], [
			'id' => $adventure,
		]);

    for($i = 0; $i < 3; $i++){
      $adv[$i]['id'] = $adventures[$i]['id'];
      $adv[$i]['name'] = $adventures[$i]['name'];
      $adv[$i]['description'] = $adventures[$i]['description'];
      $adv[$i]['reward'] = $this -> convertGold($reward[$i]);
      $adv[$i]['duration'] = $duration[$i];
    }

    return $adv;
  }

  function random_numbers($from, $to) {
    $los = array();
    for($i = $from; $i < $to; $i++){
      $los[] = $i;
    }
    shuffle($los);
    return $los;
  }

  function adventureReward($hero, $time){
    $heroes = $this -> database -> get('heroes', [
			'level',
			'luck',
		],[
			'id' => $hero -> id,
		]);

    $reward = ($heroes['level'] * $heroes['level'] + ($heroes['luck'] * rand(9, 11))) * $time;
    return $reward;
  }

  function convertGold($gold){
    $money['bronze'] = $gold % 100;
    $money['silver'] = substr($gold % 10000 - $gold % 100, 0, -2);
    $money['gold'] = substr($gold % 1000000 - $gold % 10000, 0, -4);

    return $money;
  }

  function changeAdventures($hero){
    $random = $this -> random_numbers(1, 5);
    $random = $random[0].'/'.$random[1].'/'.$random[2];

    $this -> database -> update('heroes', [
      'adventures' => $random,
      ], [
      'id' => $hero -> id,
    ]);

    $adventure = $this -> database -> get('heroes', 'adventures', [
			'id' => $hero -> id,
		]);
    $adventure = explode("/", $adventure);

    $random = $this -> random_numbers(1, 4);

    for($i = 0; $i < 3; $i++){
      $reward[] = $this -> adventureReward($hero, $adventure[$i]);
      $time[] = $adventure[$i] * $random[$i];
    }

    $reward = $reward[0].'/'.$reward[1].'/'.$reward[2];
    $time = $time[0].'/'.$time[1].'/'.$time[2];

    $this -> database -> update('heroes', [
      'adventure_reward' => $reward,
      'adventure_duration' => $time,
      ], [
      'id' => $hero -> id,
    ]);
  }
}
