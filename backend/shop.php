<?php

use Medoo\Medoo;

class Shop{

  function __construct($hero){
    $this -> hero = $hero;

    $this -> database = new Medoo([
      'database_type' => 'mysql',
      'database_name' => 'game',
      'server' => 'localhost',
      'username' => 'root',
      'password' => '',
      "charset" => "utf8",
    ]);
	}

  function convertGold($gold){
    $money['bronze'] = $gold % 100;
    $money['silver'] = substr($gold % 10000 - $gold % 100, 0, -2);
    $money['gold'] = substr($gold % 1000000 - $gold % 10000, 0, -4);

    return $money;
  }

  function newItem($ile){
    $already_used = array();

    $level = $this -> hero -> getLevel();
    $class = $this -> database -> get('heroes', 'class', [
      'id' =>  $this -> hero -> id,
    ]);


      $items = $this -> database -> select('items', '*', [
        'level[<>]' => [$level - 20, $level],
      ]);

      shuffle($items);

      for($i = 0; $i < $ile; $i++){

        $item = $items[$i];

        if(in_array($item['name'], $already_used)){
          $i--;
        }
        else{
          $already_used[] = $item['name'];
          $multipler = $level;

          $vitality = 0;
          $strength = 0;
          $intelligence = 0;
          $agility = 0;
          $luck = 0;
          $attack_min = 0;
          $attack_max = 0;
          $defense = 0;

          if( $item['type'] == 'ring' ||
              $item['type'] == 'necklace' ||
              $item['type'] == 'belt'){
            if(rand(1, 3) == 1) $vitality = $multipler / 4 + rand(1, 5);
            if(rand(1, 3) == 1) $strength = $multipler / 4 + rand(1, 5);
            if(rand(1, 3) == 1) $intelligence = $multipler / 4 + rand(1, 5);
            if(rand(1, 3) == 1) $agility = $multipler / 4 + rand(1, 5);
            if(rand(1, 3) == 1) $luck = $multipler / 4 + rand(1, 5);
          }
          else if($item['type'] == 'sword'){
            $attack_min = $multipler + rand(ceil($multipler / 6), ceil($multipler / 4));
            $attack_max = $multipler + rand(ceil($multipler / 3), ceil($multipler / 1));
          }
          else if($item['type'] == 'shield') $defense = floor(($multipler / 1.5) + rand(ceil($mulitpler / 4), ceil($multipler / 2)));

          else if($item['type'] == 'chestplate') $defense = floor(($multipler / 2) + rand(ceil($multipler / 4), ceil($multipler / 2)));

          else if($item['type'] == 'arm' ||
                  $item['type'] == 'helmet' ||
                  $item['type'] == 'feet') $defense = floor(($multipler / 5) + rand(ceil($multipler / 4), ceil($multipler / 2)));

          $cost = ($multipler * $multipler) / 5 * rand(10, 12) + ($vitality + $strength + $intelligence + $agility + $luck + $defense + $attack_min + $attack_max) + 15;

          $this -> database -> insert("eq", [
          	"name" => $item['name'],
          	"hero_id" => $this -> hero -> id,
          	"type" => $item['type'],
            "attack_min" => $attack_min,
            "attack_max" => $attack_max,
            "defense" => $defense,
            "state" => 0,
            "img" => $item['img'],
            "vitality" => $vitality,
            "strength" => $strength,
            "intelligence" => $intelligence,
            "agility" => $agility,
            "luck" => $luck,
            "cost" => $cost,
          ]);
        }
      }
  }

  function getItems(){
    $items = $this -> database -> count('eq', [
      'hero_id' =>  $this -> hero -> id,
      'state' => 0,
    ]);

    if($items != 4) $this -> newItem(4 - $items);

    $items = $this -> database -> select('eq', '*', [
      'hero_id' => $this -> hero -> id,
      'state' => 0,
    ]);

    for($i = 0; $i < 4; $i++){
      $items[$i]['cost'] = $this -> convertGold($items[$i]['cost']);
    }
    return $items;
  }

  function buyItem($id){
    $error = NULL;

    $cost = $this -> database -> get('eq', 'cost', [
      'id' => $id,
    ]);

    $gold = $this -> database -> get('heroes', 'gold', [
      'id' => $this -> hero -> id,
    ]);

    $items = $this -> database -> count('eq', [
      'AND' => [
        'hero_id' => $this -> hero -> id,
        'state[<>]' => [1, 2],
      ],
    ]);

    $item = $this -> database -> get('eq', 'state', [
      'id' => $id,
    ]);

    if($gold < $cost) $error[] = 'Nie posiadasz wystarczającej ilości złota!';
    if($items >= 8) $error[] = 'Twój ekwipunek jest zapełniony!';

    if($gold >= $cost && $items < 8 && $item == 0){
      $this -> database -> update('eq', [
        'state' => 1,
        'cost[-]' => $cost / 3,
        ], [
        'id' => $id,
      ]);

      $this -> database -> update('heroes', [
        'gold[-]' => $cost,
        ], [
        'id' => $this -> hero -> id,
      ]);
      return false;
    }

    else return $error;
  }

  function sellItem($id){
    $cost = $this -> database -> get('eq', 'cost', [
      'id' => $id,
    ]);

    $item = $this -> database -> get('eq', 'state', [
      'id' => $id,
    ]);

    if($item == 1){
      $this -> database -> delete('eq', [
        'id' => $id,
      ]);

      $this -> database -> update('heroes', [
        'gold[+]' => $cost,
        ], [
        'id' => $this -> hero -> id,
      ]);
      return true;
    }
    else return false;
  }
}
