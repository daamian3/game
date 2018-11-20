<?php

use Medoo\Medoo;

class Adventure{
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

  function end(){
    $reward = $this -> database -> get('heroes', 'adventure_reward', [
      'id' => $this -> hero -> id,
    ]);

    $this -> database -> update('heroes', [
      'gold[+]' => $reward,
      'adventures_traveled[+]' => 1,
      ], [
      'id' => $this -> hero -> id,
    ]);

    $this -> changeAdventures();
    $this -> database -> update('heroes', [
      'adventure_time' => 0,
      ], [
      'id' => $this -> hero -> id,
    ]);
    return false;
  }

  function isStarted(){
    $end = $this -> database -> get('heroes', 'adventure_time', [
      'id' => $this -> hero -> id,
    ]);

    if($end == 0) return false;
    else{
      $end = new DateTime($end);
      $now = new DateTime();

      $diff = $end -> format('U') - $now -> format('U');
      if($diff > 0) return true;
      else return $this -> end();
    }
  }

  function getAdventures(){
    $adventures = $this -> database -> get('heroes', [
      'adventures',
      'adventure_reward',
      'adventure_duration',
    ], [
      'id' => $this -> hero -> id,
    ]);

    if($adventures['adventures'] == NULL){
      $this -> changeAdventures();
      $adventures = $this -> database -> get('heroes', [
        'adventures',
        'adventure_reward',
        'adventure_duration',
      ], [
  			'id' => $this -> hero -> id,
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

  function getAdventure($n = NULL){
    if(is_null($n)){
      $adv1 = $this -> database -> get('heroes', [
        'adventures',
        'adventure_reward',
        'adventure_duration',
        'adventure_time',
      ], [
        'id' => $this -> hero -> id,
      ]);
      $time = new DateTime($adv1['adventure_time']);
      $time -> format('U');
      $adv1['adventure_time'] = $time -> getTimestamp();
      $adv2 = $this -> database -> get('adventures', [
        'name',
        'description',
      ], [
        'id' => $adv1['adventures'],
      ]);

      return array_merge($adv1, $adv2);
    }
    else if($n == 0 || $n == 1 || $n == 2){
      $adventures = $this -> getAdventures();

      $adv['id'] = $adventures[$n]['id'];
      $adv['name'] = $adventures[$n]['name'];
      $adv['description'] = $adventures[$n]['description'];
      $adv['reward'] = $adventures[$n]['reward'];
      $adv['duration'] = $adventures[$n]['duration'];

      return $adv;
      if(is_null($adv['id'])) var_dump($this -> database -> error());
    }
    else echo 'Error: Zły numer przygody!';
  }

  function random_numbers($from, $to) {
    $los = array();
    for($i = $from; $i < $to; $i++){
      $los[] = $i;
    }
    shuffle($los);
    return $los;
  }

  function adventureReward($time){
    $heroes = $this -> database -> get('heroes', [
			'level',
			'luck',
		],[
			'id' => $this -> hero -> id,
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

  function start($n){
    $adventure = $this -> getAdventure($n);
    $time = new DateTime();
    $time -> modify("+".$adventure['duration']." hours");
    $duration = $time -> format("Y-m-d H:i:s");

    $reward = $adventure['reward']['bronze'] + ($adventure['reward']['silver'] * 100) + ($adventure['reward']['gold'] * 10000);

    $this -> database -> update('heroes', [
      'adventures' => $adventure['id'],
      'adventure_time' => $duration,
      'adventure_reward' => $reward,
      'adventure_duration' => $adventure['duration'],
      ], [
      'id' => $this -> hero -> id,
    ]);
  }

  function changeAdventures(){
    $random = $this -> random_numbers(1, 5);
    $random = $random[0].'/'.$random[1].'/'.$random[2];

    $this -> database -> update('heroes', [
      'adventures' => $random,
      ], [
      'id' => $this -> hero -> id,
    ]);

    $adventure = $this -> database -> get('heroes', 'adventures', [
			'id' => $this -> hero -> id,
		]);
    $adventure = explode("/", $adventure);

    $random = $this -> random_numbers(1, 4);

    for($i = 0; $i < 3; $i++){
      $reward[] = $this -> adventureReward($adventure[$i]);
      $time[] = $adventure[$i] * $random[$i];
    }

    $reward = $reward[0].'/'.$reward[1].'/'.$reward[2];
    $time = $time[0].'/'.$time[1].'/'.$time[2];

    $this -> database -> update('heroes', [
      'adventure_reward' => $reward,
      'adventure_duration' => $time,
      ], [
      'id' => $this -> hero -> id,
    ]);
  }
}
