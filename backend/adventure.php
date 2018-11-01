<?php
class Adventure{
  function random_numbers($from, $to) {
    $los = array();
    for($i = $from; $i < $to; $i++){
      $los[] = $i;
    }
    shuffle($los);
    return $los;
  }

  function adventureCost($hero, $time){
    $level = $this -> database -> get('heroes', [
			'level',
			'luck',
		],[
			'id' => $hero -> id,
		]);

    $level = pobierz_wartosc('level', 'heroes', 'id = ?', $hero -> id);
    $luck = pobierz_wartosc('luck', 'heroes', 'id = ?', $hero -> id);

    $cost = ($level * $level + ($luck * rand(9, 11))) * $time;
    return $cost;
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
    ustal_wartosc('adventures', '"'.$random.'"', 'heroes', 'id = ?',  $hero -> id);

    $adventure = pobierz_wartosc('adventures', 'heroes', 'id = ?', $hero -> id);
    $adventure = explode("/", $adventure);

    $random = $this -> random_numbers(1, 4);

    for($i = 0; $i < 3; $i++){
      $reward[] = $this -> adventureCost($hero, $adventure[$i]);
      $time[] = $adventure[$i] * $random[$i];
    }

    $reward = $reward[0].'/'.$reward[1].'/'.$reward[2];
    $time = $time[0].'/'.$time[1].'/'.$time[2];

    ustal_wartosc('adventure_reward', '"'.$reward.'"', 'heroes', 'id = ?',  $hero -> id);
    ustal_wartosc('adventure_duration', '"'.$time.'"', 'heroes', 'id = ?',  $hero -> id);
  }

  function __construct($hero){
    $adventure = pobierz_wartosc('adventures', 'heroes', 'id = ?', $hero -> id);
    if($adventure == NULL){
      $this -> changeAdventures($hero);
      $adventure = pobierz_wartosc('adventures', 'heroes', 'id = ?', $hero -> id);
    }

    $adventure = explode("/", $adventure);

    $reward = pobierz_wartosc('adventure_reward', 'heroes', 'id = ?', $hero -> id);
    $reward = explode("/", $reward);
    $time = pobierz_wartosc('adventure_duration', 'heroes', 'id = ?', $hero -> id);
    $time = explode("/", $time);

    for($i = 0; $i < 3; $i++){
      $this -> $i = new stdClass;
      $this -> $i -> id = pobierz_wartosc('id', 'adventures', 'id = ?', $adventure[$i]);
      $this -> $i -> name = pobierz_wartosc('name', 'adventures', 'id = ?', $adventure[$i]);
      $this -> $i -> description = pobierz_wartosc('description', 'adventures', 'id = ?', $adventure[$i]);
      $this -> $i -> reward = $this -> convertGold($reward[$i]);
      $this -> $i -> time = $time[$i];
    }
    return $this;
  }
}
