<?php

class Shop{

  function __construct($hero){
    $this -> hero = $hero;
	}

  function convertGold($gold){
    $money['bronze'] = $gold % 100;
    $money['silver'] = substr($gold % 10000 - $gold % 100, 0, -2);
    $money['gold'] = substr($gold % 1000000 - $gold % 10000, 0, -4);

    return $money;
  }

  function newItem($ile){

    $class = pobierz_wartosc('class', 'heroes', 'id = ?', $this -> hero -> id);

    for($j = 0; $j < $ile; $j++){
      $id = pobierz_wartosc('COUNT(id)', 'items', 'level <= ?', $this -> hero -> getLevel());
      $i = 1;

      do{
        $item = pobierz_wartosc('*', 'items', 'level <= ? AND id = ?', $this -> hero -> getLevel(), $i);
        if($item){
          $i++;
          $items[] = $item;
        }
        else break; //TODO nie rozwiązałem problemu nieskończonej pętli na 1 lvl.
      }while($i < $id);

      shuffle($items);
      $item = $items[0];

      $multipler = pobierz_wartosc('level', 'heroes', 'id = ?', $this -> hero -> id);

      $vitality = 0;
      $strength = 0;
      $intelligence = 0;
      $agility = 0;
      $luck = 0;

      if( $item['type'] == 'ring' ||
          $item['type'] == 'necklace' ||
          $item['type'] == 'belt'){
        if(rand(1, 3) == 1) $vitality = $multipler / 4 + rand(1, 5);
        if(rand(1, 3) == 1) $strength = $multipler / 4 + rand(1, 5);
        if(rand(1, 3) == 1) $intelligence = $multipler / 4 + rand(1, 5);
        if(rand(1, 3) == 1) $agility = $multipler / 4 + rand(1, 5);
        if(rand(1, 3) == 1) $luck = $multipler / 4 + rand(1, 5);
      }

      dodaj_wartosc('name, hero_id, type, attack_min, attack_max, defense, state', 'eq', $item['name'], $this -> hero -> id, $item['type'], $item['attack_min'], $item['attack_max'], $item['defense'], true);
      ustal_wartosc('img', '"'.$item['img'].'"', 'eq', 'name = ? AND hero_id = ?', $item['name'], $this -> hero -> id);
      ustal_wartosc('vitality', $vitality, 'eq', 'name = ? AND hero_id = ?', $item['name'], $this -> hero -> id);
      ustal_wartosc('strength', $strength, 'eq', 'name = ? AND hero_id = ?', $item['name'], $this -> hero -> id);
      ustal_wartosc('intelligence', $intelligence, 'eq', 'name = ? AND hero_id = ?', $item['name'], $this -> hero -> id);
      ustal_wartosc('agility', $agility, 'eq', 'name = ? AND hero_id = ?', $item['name'], $this -> hero -> id);
      ustal_wartosc('luck', $luck, 'eq', 'name = ? AND hero_id = ?', $item['name'], $this -> hero -> id);

      $cost = ($multipler * $multipler) / 5 * rand(10, 12) + ($vitality + $strength + $intelligence + $agility + $luck);

      ustal_wartosc('cost', $cost, 'eq', 'name = ? AND hero_id = ?', $item['name'], $this -> hero -> id);
    }
  }

  function getItems(){
    $items = pobierz_wartosc('COUNT(id)', 'eq', 'hero_id = ? AND state = 1', $this -> hero -> id);

    if($items != 4) $this -> newItem(4 - $items);

    $items = pobierz_wartosc('*', 'eq', 'hero_id = ? AND state = 1', $this -> hero -> id, NULL, NULL, NULL, true);

    for($i = 0; $i < 4; $i++){
      $items[$i]['cost'] = $this -> convertGold($items[$i]['cost']);
    }

    return $items;
  }

  function buyItem($id){
    $cost = pobierz_wartosc('cost', 'eq', 'id = ?', $id);
    $gold = pobierz_wartosc('gold', 'heroes', 'id = ?', $this -> hero -> id);
    $items = pobierz_wartosc('COUNT(id)', 'eq', 'hero_id = ? AND state = 0', $this -> hero -> id);
    $item = pobierz_wartosc('state', 'eq', 'id = ?', $id);

    if($gold < $cost) $error[] = 'Nie posiadasz wystarczającej ilości złota!';
    if($items > 8) $error[] = 'Twój ekwipunek jest zapełniony!';

    if($gold >= $cost && $items <= 8 && $item == 1){
      ustal_wartosc('state', 0, 'eq', 'id = ?', $id);
      ustal_wartosc('gold', $gold - $cost, 'heroes', 'id = ?', $this -> hero -> id);
      return false;
    }

    else return $error;
  }
}
