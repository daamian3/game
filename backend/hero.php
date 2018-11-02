<?php

use Medoo\Medoo;

class Hero{

  function __construct($id = NULL){
		if($id == NULL) $this -> id = $_SESSION['id'];
    else $this -> id = $id;

    $this -> database = new Medoo([
        'database_type' => 'mysql',
        'database_name' => 'game',
        'server' => 'localhost',
        'username' => 'root',
        'password' => '',
        "charset" => "utf8",
    ]);
	}

  function getLevel(){
    return $this -> database -> get('heroes', 'level', [
      'id' =>  $this -> id,
    ]);
  }

  function getBag(){
    $eq = $this -> database -> select('eq', '*', [
      'hero_id' =>  $this -> id,
      'state' => 1,
    ]);

    $count = count($eq);
    $items = array_fill($count, 8 - $count, 0);

    return array_merge($eq, $items);
  }

  function getEq(){

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

    $equiped = $this -> database -> select('eq', '*', [
      'hero_id' =>  $this -> id,
      'state' => 2,
    ]);

    $sword_count = 0;

    foreach($equiped as $item){
      $eq[$item['type']] = $item;

      if($item['type'] == "sword"){
        $sword_count++;
        if($sword_count > 1) $eq['hand2'] = $item;
        else $eq['hand1'] = $item;
      }
    }

    return $eq;
  }

  function getVitality($eq){
    if($eq == NULL) $eq = $this -> getEq();

    $class_multipler = $this -> database -> get('classes', 'vitality', [
      'name' =>  $this -> klasa,
    ]);

    $race_multipler = $this -> database -> get('races', 'vitality', [
      'name' =>  $this -> race,
    ]);

    $vitality = $this -> database -> get('heroes', 'vitality', [
      'id' =>  $this -> id,
    ]);

    $this -> vitality = floor($vitality * $class_multipler * $race_multipler + $eq['ring1']['vitality'] + $eq['ring2']['vitality'] + $eq['belt']['vitality'] + $eq['necklace']['vitality'] + 1);

    return $this -> vitality;
  }

  function getTriple($eq){
    if($eq == NULL) $eq = $this -> getEq();

    $class_multipler = $this -> database -> get('classes', [
      'strength',
      'intelligence',
      'agility',
    ],[
      'name' =>  $this -> klasa,
    ]);

    $race_multipler = $this -> database -> get('races', [
      'strength',
      'intelligence',
      'agility',
    ],[
      'name' =>  $this -> race,
    ]);

    $stats = $this -> database -> get('heroes', [
      'strength',
      'intelligence',
      'agility',
    ],[
      'id' =>  $this -> id,
    ]);

    $this -> strength = floor($stats['strength'] * $class_multipler['strength'] * $race_multipler['strength'] + $eq['ring1']['strength'] + $eq['ring2']['strength'] + $eq['belt']['strength'] + $eq['necklace']['strength'] + 1);

    $this -> intelligence = floor($stats['intelligence'] * $class_multipler['intelligence'] * $race_multipler['intelligence'] + $eq['ring1']['intelligence'] + $eq['ring2']['intelligence'] + $eq['belt']['intelligence'] + $eq['necklace']['intelligence'] + 1);

    $this -> agility = floor($stats['agility'] * $class_multipler['agility'] * $race_multipler['agility'] + $eq['ring1']['agility'] + $eq['ring2']['agility'] + $eq['belt']['agility'] + $eq['necklace']['agility'] + 1);

    $triple = [
      'strength' => $this -> strength,
      'intelligence' => $this -> intelligence,
      'agility' => $this -> agility
    ];

    return $triple;
  }

  function getLuck($eq){
    if($eq == NULL) $eq = $this -> getEq();

    $luck = $this -> database -> get('heroes', 'luck', [
     'id' =>  $this -> id,
    ]);

    $this -> luck = floor($luck + $eq['ring1']['luck'] + $eq['ring2']['luck'] + $eq['belt']['luck'] + $eq['necklace']['luck'] + 1);

    return $this -> luck;
  }

  function getHealth($eq = NULL){
    if($eq == NULL) $eq = $this -> getEq();

    $health = $this -> vitality * 7 + (3 * ($eq['ring1']['vitality'] + $eq['ring2']['vitality'] + $eq['belt']['vitality'] + $eq['necklace']['vitality'] + 1));

    return floor($health);
  }

  function getAttack_min($eq = NULL){
    if($eq == NULL) $eq = $this -> getEq();

    if(!isset($this -> strength) || !isset($this -> intelligence) || !isset($this -> agility)) getTriple($eq);

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
    $attack_min = 0.04 * $stats * ($eq['hand1']['attack_min'] + $eq['hand2']['attack_min']) + $stats;

    return floor($attack_min);
  }

  function getAttack_max($eq = NULL){
    if($eq == NULL) $eq = $this -> getEq();

    if(!isset($this -> strength) || !isset($this -> intelligence) || !isset($this -> agility)) getTriple($eq);

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

    if(!isset($this -> luck)) $this -> getLuck($eq);

    $critical = $this -> luck * 0.3 * ( 0.1 * ($eq['ring1']['luck'] + $eq['ring2']['luck'] + $eq['belt']['luck'] + $eq['necklace']['luck'] + 1));

    if($critical < 1) $critical = 1;

    return floor($critical);
  }

  function getMiss($eq = NULL){
    if($eq == NULL) $eq = $this -> getEq();
    if(!isset($this -> agility)) $this -> getTriple($eq);

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
    if($gold == NULL) $gold = $this -> database -> get('heroes', 'gold', [
      'id' =>  $this -> id,
    ]);

    $money = $this -> convertGold($gold);

    $this -> bronze = $money['bronze'];
    $this -> silver = $money['silver'];
    $this -> gold = $money['gold'];

    return $money;
  }

  function getStats(){
    $stats = $this -> database -> get('heroes', '*', [
      'id' => $this -> id,
    ]);

    $exp = $this -> database -> get('experience', 'experience', [
      'level' => $stats['level'] + 1,
    ]);

    $class = $this -> database -> get('heroes', 'class', [
      'id' => $this -> id,
    ]);

    $race = $this -> database -> get('heroes', 'race', [
      'id' => $this -> id,
    ]);

    $this -> name = $stats['name'];
    $this -> killed_monsters = $stats['killed_monsters'];
    $this -> beated_players = $stats['beated_players'];
    $this -> level = $stats['level'];
    $this -> experience = $stats['experience'];
    $this -> experience_max = $exp;
    $this -> dungeon_time = strtotime($stats['dungeon_time']);
    $this -> klasa = $class;
    $this -> race = $race;
    $this -> money = $stats['gold'];
    $this -> getMoney();

    $this -> vitality_cost = $this -> getAttribCost('vitality');
    $this -> strength_cost = $this -> getAttribCost('strength');
    $this -> intelligence_cost = $this -> getAttribCost('intelligence');
    $this -> agility_cost = $this -> getAttribCost('agility');
    $this -> luck_cost = $this -> getAttribCost('luck');

    $eq = $this -> getEq();

    $this -> getVitality($eq);
    $this -> getTriple($eq);
    $this -> getLuck($eq);

		$this -> health = $this -> getHealth($eq);
		$this -> attack_min = $this -> getAttack_min($eq);
		$this -> attack_max = $this -> getAttack_max($eq);
		$this -> defense = $this -> getDefense($eq);
		$this -> critical = $this -> getCritical($eq);
		$this -> miss = $this -> getMiss($eq);
    $this -> levelUp();
    return $this;
  }

  function attribUp($attribute){
    $attrib = $this -> database -> get('heroes', $attribute, [
      'id' => $this -> id,
    ]) + 1;

    $cost = $this -> database -> get('experience', 'experience', [
      'level' => $attrib,
    ]);

    $gold = $this -> database -> get('heroes', 'gold', [
      'id' => $this -> id,
    ]);

    if($gold >= $cost){

      $this -> database -> update('heroes', [
        $attribute => $attrib,
      ],[
        'id' => $this -> id,
      ]);

      $this -> database -> update('heroes', [
        "gold[-]" => $cost,
      ],[
        'id' => $this -> id,
      ]);

      return $attrib + 1;
    }
    else return false;
  }

  function getAttribCost($attribute){

    $attrib = $this -> database -> get('heroes', $attribute, [
      'id' => $this -> id,
    ]) + 1;

    $gold = $this -> database -> get('experience', 'experience', [
      'level' => $attrib,
    ]);

    $money = $this -> convertGold($gold);

    return $money;
  }

  function levelUp(){
    $level = $this -> getLevel();

    $exp = $this -> database -> get('heroes', 'experience', [
      'id' => $this -> id,
    ]);

    $cost = $this -> database -> get('experience', 'experience', [
      'level' => $level + 1,
    ]);

    if($exp >= $cost){
      $this -> database -> update('heroes', [
        "level[+]" => 1,
      ],[
        'id' => $this -> id,
      ]);

      $this -> database -> update('heroes', [
        "experience[-]" => $cost,
      ],[
        'id' => $this -> id,
      ]);
    }
  }

  function setDungeonTime(){
    $this -> database->query(
	    'UPDATE <heroes> SET <dungeon_time> = "NOW() + INTERVAL 1 HOUR" WHERE <id> = :id', [
		      ":id" => $this -> id,
	    ]);
  }

  function dungeonIsAvailable(){
    date_default_timezone_set("Europe/Warsaw");
    $dungeon_time = $this -> database -> get('heroes', 'dungeon_time', [
      'id' => $this -> id,
    ]);
    $dungeon_time = strtotime($dungeon_time);
    $time = time();

    if($dungeon_time <= $time) return true;
    else return false;
  }

  function equipItem($id){
    $hero_id = $this -> database -> get('eq', 'hero_id', [
      'id' => $id,
    ]);

    if($hero_id == $this -> id){
      $item_type =  $this -> database -> get('eq', 'type', [
        'id' => $id
      ]);

      $item_using = $this -> database -> get('eq', 'id', [
        "AND" => [
          'hero_id' => $hero_id,
          'type' => $item_type,
          'state' => 2,
        ]
      ]);

      if($item_using > 0 && $item_type != 'sword' && $item_type != 'ring') $this -> database -> update('eq', [
        "state" => 1,
      ],[
        'id' => $item_using,
      ]);

      $lolek = $this -> database -> update('eq', [
        "state" => 2,
      ],[
        'id' => $id,
      ]);

      return true;
    }
    else return false;
  }

  function unEquipItem($type){
    $this -> database -> update('eq',[
      "state" => 1,
      ], [
      "AND" => [
        'hero_id' => $this -> id,
        'type' => $type,
        'state' => 2,
      ],
      'LIMIT' => 1,
    ]);

    //TODO przy dwoch mieczach tylko drugi jest sciagany, tak samo pierscienie
    return true;
  }
}
