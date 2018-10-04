<?php

require_once 'db_functions.php';

class Hero{
  function __construct($id = NULL){
		if($id == NULL) $this -> id = $_SESSION['id'];
    else $this -> id = $id;
	}

  function getLevel(){
    return pobierz_wartosc('level', 'heroes', 'id = ?', $this -> id);
  }

  function getBag(){
    return $items = pobierz_wartosc('*', 'eq', 'hero_id = ? AND shop = false', $this -> id, NULL, NULL, NULL, true);
  }

  function getEq(){
    $stats = pobierz_wartosc('*', 'heroes', 'id = ?', $this -> id);

    $eq = array(
      'helmet' => array(
        'strength' => 0,
        'agility' => 0,
        'intelligence' => 0,
        'vitality' => 0,
        'luck' => 0,
        'defense' => 0,
      ),
      'chestplate' => array(
        'strength' => 0,
        'agility' => 0,
        'intelligence' => 0,
        'vitality' => 0,
        'luck' => 0,
        'defense' => 0,
      ),
      'hand1' => array(
        'strength' => 0,
        'agility' => 0,
        'intelligence' => 0,
        'vitality' => 0,
        'luck' => 0,
        'attack_min' => 2,
        'attack_max' => 2,
        'defense' => 0,
      ),
      'hand2' => array(
        'strength' => 0,
        'agility' => 0,
        'intelligence' => 0,
        'vitality' => 0,
        'luck' => 0,
        'attack_min' => 2,
        'attack_max' => 2,
        'defense' => 0,
      ),
      'necklace' => array(
        'strength' => 0,
        'agility' => 0,
        'intelligence' => 0,
        'vitality' => 0,
        'luck' => 0,
        'defense' => 0,
      ),
      'arm' => array(
        'strength' => 0,
        'agility' => 0,
        'intelligence' => 0,
        'vitality' => 0,
        'luck' => 0,
        'defense' => 0,
      ),
      'belt' => array(
        'strength' => 0,
        'agility' => 0,
        'intelligence' => 0,
        'vitality' => 0,
        'luck' => 0,
        'defense' => 0,
      ),
      'feet' => array(
        'strength' => 0,
        'agility' => 0,
        'intelligence' => 0,
        'vitality' => 0,
        'luck' => 0,
        'defense' => 0,
      ),
      'ring1' => array(
        'strength' => 0,
        'agility' => 0,
        'intelligence' => 0,
        'vitality' => 0,
        'luck' => 0,
        'defense' => 0,
      ),
      'ring2' => array(
        'strength' => 0,
        'agility' => 0,
        'intelligence' => 0,
        'vitality' => 0,
        'luck' => 0,
        'defense' => 0,
      ),
    );

    if (!is_null($stats['helmet_id'])) $eq['helmet'] = pobierz_wartosc('*', 'eq', 'id = ?', $stats['helmet_id']);
    if (!is_null($stats['chestplate_id'])) $eq['chestplate'] = pobierz_wartosc('*', 'eq', 'id = ?', $stats['chestplate_id']);
    if (!is_null($stats['hand1_id'])) $eq['hand1'] = pobierz_wartosc('*', 'eq', 'id = ?', $stats['hand1_id']);
    if (!is_null($stats['hand2_id'])) $eq['hand2'] = pobierz_wartosc('*', 'eq', 'id = ?', $stats['hand2_id']);
    if (!is_null($stats['feet_id'])) $eq['feet'] = pobierz_wartosc('*', 'eq', 'id = ?', $stats['feet_id']);
    if (!is_null($stats['arm_id'])) $eq['arm'] = pobierz_wartosc('*', 'eq', 'id = ?', $stats['arm_id']);
    if (!is_null($stats['belt_id'])) $eq['belt'] = pobierz_wartosc('*', 'eq', 'id = ?', $stats['belt_id']);
    if (!is_null($stats['ring1_id'])) $eq['ring1'] = pobierz_wartosc('*', 'eq', 'id = ?', $stats['ring1_id']);
    if (!is_null($stats['ring2_id'])) $eq['ring2'] = pobierz_wartosc('*', 'eq', 'id = ?', $stats['ring2_id']);
    if (!is_null($stats['necklace_id'])) $eq['necklace'] = pobierz_wartosc('*', 'eq', 'id = ?', $stats['necklace_id']);

    return $eq;
  }

  function getHealth($eq = NULL){
    if($eq == NULL) $eq = $this -> getEq();

    if(!isset($this -> vitality)){
      $class = pobierz_wartosc('*', 'classes', 'name = ?', $stats['class']);
      $race = pobierz_wartosc('*', 'races', 'name = ?', $stats['race']);
      $this -> vitality = pobierz_wartosc('vitality', 'heroes', 'id = ?', $this -> id) * $class['vitality'] * $race['vitality'];
    }

    $health = $this -> vitality * 7 + (6 * ($eq['ring1']['vitality'] + $eq['ring2']['vitality'] + $eq['belt']['vitality'] + $eq['necklace']['vitality'] + 1));

    return floor($health);
  }

  function getAttack_min($eq = NULL){
    if($eq == NULL) $eq = $this -> getEq();

    if(!isset($this -> strength) || !isset($this -> intelligence) || !isset($this -> agility)){
      $class = pobierz_wartosc('*', 'classes', 'name = ?', $stats['class']);
      $race = pobierz_wartosc('*', 'races', 'name = ?', $stats['race']);

      if(!isset($this -> strength)) $this -> strength = pobierz_wartosc('strength', 'heroes', 'id = ?', $this -> id) * $class['strength'] * $race['strength'] + 1;

      if(!isset($this -> intelligence)) $this -> intelligence = pobierz_wartosc('intelligence', 'heroes', 'id = ?', $this -> id) * $class['intelligence'] * $race['intelligence'] + 1;

      if(!isset($this -> agility)) $this -> agility = pobierz_wartosc('agility', 'heroes', 'id = ?', $this -> id) * $class['agility'] * $race['agility'] + 1;
    }

    switch($this -> klasa){
      case "warrior":
        $stats = $this -> strength + $eq['ring1']['strength'] + $eq['ring2']['strength'] + $eq['belt']['strength'] + $eq['necklace']['strength'] + 1;
        break;
      case "mage":
        $stats = $this -> intelligence + $eq['ring1']['intelligence'] + $eq['ring2']['intelligence'] + $eq['belt']['intelligence'] + $eq['necklace']['intelligence'] + 1;
        break;
      case "ranger":
        $stats = $this -> agility + $eq['ring1']['agility'] + $eq['ring2']['agility'] + $eq['belt']['agility'] + $eq['necklace']['agility'] + 1;
        break;
    }
    $attack_min = 0.05 * $stats * ($eq['hand1']['attack_min'] + $eq['hand2']['attack_min']) + $stats;

    return floor($attack_min);
  }

  function getAttack_max($eq = NULL){
    if($eq == NULL) $eq = $this -> getEq();

    if(!isset($this -> strength) || !isset($this -> intelligence) || !isset($this -> agility)){
      $class = pobierz_wartosc('*', 'classes', 'name = ?', $stats['class']);
      $race = pobierz_wartosc('*', 'races', 'name = ?', $stats['race']);

      if(!isset($this -> strength)) $this -> strength = pobierz_wartosc('strength', 'heroes', 'id = ?', $this -> id) * $class['strength'] * $race['strength'] + 1;

      if(!isset($this -> intelligence)) $this -> intelligence = pobierz_wartosc('intelligence', 'heroes', 'id = ?', $this -> id) * $class['intelligence'] * $race['intelligence'] + 1;

      if(!isset($this -> agility)) $this -> agility = pobierz_wartosc('agility', 'heroes', 'id = ?', $this -> id) * $class['agility'] * $race['agility'] + 1;
    }

    switch($this -> klasa){
      case "warrior":
        $stats = $this -> strength + $eq['ring1']['strength'] + $eq['ring2']['strength'] + $eq['belt']['strength'] + $eq['necklace']['strength'] + 1;
        break;
      case "mage":
        $stats = $this -> intelligence + $eq['ring1']['intelligence'] + $eq['ring2']['intelligence'] + $eq['belt']['intelligence'] + $eq['necklace']['intelligence'] + 1;
        break;
      case "ranger":
        $stats = $this -> agility + $eq['ring1']['agility'] + $eq['ring2']['agility'] + $eq['belt']['agility'] + $eq['necklace']['agility'] + 1;
        break;
    }
    $attack_max = 0.05 * $stats * ($eq['hand1']['attack_max'] + $eq['hand2']['attack_max']) + $stats;

    return floor($attack_max);
  }

  function getDefense($eq = NULL){
    if($eq == NULL) $eq = $this -> getEq();

    $defense = $eq['chestplate']['defense'] + $eq['helmet']['defense'] + $eq['belt']['defense'] + $eq['arm']['defense'] + $eq['feet']['defense'] + $eq['hand2']['defense'];

    return floor($defense);
  }

  function getCritical($eq = NULL){
    if($eq == NULL) $eq = $this -> getEq();
    if(!isset($this -> luck)) $this -> vitality = pobierz_wartosc('luck', 'heroes', 'id = ?', $this -> id);

    $critical = $this -> luck * 0.3 * ( 0.1 * ($eq['ring1']['luck'] + $eq['ring2']['luck'] + $eq['belt']['luck'] + $eq['necklace']['luck'] + 1));

    if($critical < 1) $critical = 1;

    return floor($critical);
  }

  function getMiss($eq = NULL){
    if($eq == NULL) $eq = $this -> getEq();
    if(!isset($this -> agility)) $this -> agility = pobierz_wartosc('agility', 'heroes', 'id = ?', $this -> id);

    $miss = $this -> agility * 0.3 * ( 0.1 * ($eq['ring1']['agility'] + $eq['ring2']['agility'] + $eq['belt']['agility'] + $eq['necklace']['agility'] + 1));

    if($miss < 1) $miss = 1;

    return floor($miss);
  }

  function convertGold($gold){
    $money['bronze'] = $gold % 100;
    $money['silver'] = substr($gold % 10000 - $gold % 100, 0, -2);
    $money['gold'] = substr($gold % 1000000 - $gold % 10000, 0, -4);

    return $money;
  }

  function getMoney($gold = NULL){
    if($gold == NULL) $gold = pobierz_wartosc('gold', 'heroes', 'id = ?', $this -> id);

    $money = $this -> convertGold($gold);

    $this -> bronze = $money['bronze'];
    $this -> silver = $money['silver'];
    $this -> gold = $money['gold'];

    return $money;
  }

  function getStats(){
    $stats = pobierz_wartosc('*', 'heroes', 'id = ?', $this -> id);
    $exp = pobierz_wartosc('experience', 'experience', 'level = ?', $stats['level'] + 1);
    $class = pobierz_wartosc('*', 'classes', 'name = ?', $stats['class']);
    $race = pobierz_wartosc('*', 'races', 'name = ?', $stats['race']);

    $stats['vitality'] *= $class['vitality'] * $race['vitality'];
    $stats['intelligence'] *= $class['intelligence'] * $race['intelligence'];
    $stats['strength'] *= $class['strength'] * $race['strength'];
    $stats['agility'] *= $class['agility'] * $race['agility'];

    $this -> name = $stats['name'];
    $this -> vitality = $stats['vitality'];
    $this -> strength = $stats['strength'];
    $this -> intelligence = $stats['intelligence'];
    $this -> agility = $stats['agility'];
    $this -> luck = $stats['luck'];
    $this -> killed_monsters = $stats['killed_monsters'];
    $this -> beated_players = $stats['beated_players'];
    $this -> level = $stats['level'];
    $this -> experience = $stats['experience'];
    $this -> experience_max = $exp;
    $this -> dungeon_time = strtotime($stats['dungeon_time']);
    $this -> klasa = $class['name'];
    $this -> race = $race['name'];
    $this -> money = $stats['gold'];
    $this -> getMoney($stats['gold']);
    $this -> vitality_cost = $this -> getAttribCost('vitality');
    $this -> strength_cost = $this -> getAttribCost('strength');
    $this -> intelligence_cost = $this -> getAttribCost('intelligence');
    $this -> agility_cost = $this -> getAttribCost('agility');
    $this -> luck_cost = $this -> getAttribCost('luck');

    $eq = $this -> getEq();

    $health = $this -> getHealth($eq);
    $attack_min = $this -> getAttack_min($eq);
    $attack_max = $this -> getAttack_max($eq);
    $defense = $this -> getDefense($eq);
    $critical = $this -> getCritical($eq);
    $miss = $this -> getMiss($eq);

    $this -> vitality = floor($stats['vitality']);
    $this -> strength = floor($stats['strength']);
    $this -> intelligence = floor($stats['intelligence']);
    $this -> agility = floor($stats['agility']);
    $this -> luck = floor($stats['luck']);
		$this -> health = $health;
		$this -> attack_min = $attack_min;
		$this -> attack_max = $attack_max;
		$this -> defense = $defense;
		$this -> critical = $critical;
		$this -> miss = $miss;

    return $this;
  }

  function attribUp($attribute){
    $attrib = pobierz_wartosc($attribute, 'heroes', 'id = ?', $this -> id) + 1;
    $cost = pobierz_wartosc('experience', 'experience', 'level = ?', $attrib);
    $gold = pobierz_wartosc('gold', 'heroes', 'id = ?', $this -> id);

    if($gold >= $cost){
      ustal_wartosc($attribute, $attrib + 1, 'heroes', 'id = ?', $this -> id);
      ustal_wartosc('gold', $gold - $cost, 'heroes', 'id = ?', $this -> id);
      return $attrib + 1;
    }
    else return false;
  }

  function getAttribCost($attribute){
    $attrib = pobierz_wartosc($attribute, 'heroes', 'id = ?', $this -> id) + 1;
    $gold = pobierz_wartosc('experience', 'experience', 'level = ?', $attrib);

    $money = $this -> convertGold($gold);

    return $money;
  }

  function levelUp(){
    $level = pobierz_wartosc('level', 'heroes', 'id = ?', $this -> id);
    $exp = pobierz_wartosc('experience', 'heroes', 'id = ?', $this -> id);
    $exp_next = pobierz_wartosc('experience', 'experience', 'level = ?', $level + 1);

    if($exp >= $exp_next){
      ustal_wartosc('level', 'level + 1', 'heroes', 'id = ?', $this -> id);
      ustal_wartosc('experience', 'experience -'.$exp_next, 'heroes', 'id = ?', $this -> id);
    }
  }

  function setDungeonTime(){
    return ustal_wartosc('dungeon_time', 'NOW() + INTERVAL 1 HOUR', 'heroes', 'id = ?', $this -> id);
  }

  function dungeonIsAvailable(){
    date_default_timezone_set("Europe/Warsaw");
    $dungeon_time = strtotime(pobierz_wartosc('dungeon_time', 'heroes', 'id = ?', $this -> id));
    $time = time();

    if($dungeon_time <= $time) return true;
    else return false;
  }

  function equipItem($id){

  }
}
