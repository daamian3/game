<?php

use Medoo\Medoo;

class Fight{
	function __construct($hero, $enemy){
		$this -> database = new Medoo([
      'database_type' => 'mysql',
      'database_name' => 'game',
      'server' => 'localhost',
      'username' => 'root',
      'password' => '',
      "charset" => "utf8",
		]);

		$this -> hero = $hero;
		$hero = $hero -> getStats();

		$this -> enemy = $enemy;
		$enemy = $enemy -> getStats();

		$this -> result = array();
		$this -> result[0] = false;
		$this -> startFight();
	}

	function checkType(){
		$check = $this -> database -> count('heroes', [
			'name' => $this -> enemy -> name,
		]);

		if($check > 0) $type = 'player';
		else{
			$check = $this -> database -> count('dungeons', [
				'OR' => [
					'stage_1' => $this -> enemy -> id,
					'stage_2' => $this -> enemy -> id,
					'stage_3' => $this -> enemy -> id,
					'stage_4' => $this -> enemy -> id,
					'stage_5' => $this -> enemy -> id,
					'stage_6' => $this -> enemy -> id,
					'stage_7' => $this -> enemy -> id,
					'stage_8' => $this -> enemy -> id,
					'stage_9' => $this -> enemy -> id,
					'stage_10' => $this -> enemy -> id,
				],
			]);
			if($check > 0) $type = 'dungeon';
			else{
				$check = $this -> database -> count('monsters', [
					'id' => $this -> enemy -> id,
				]);
				if($check > 0) $type = 'monster';
				else $type = 'not exist';
			}
		}
		return $type;

	}

	function winDungeon(){
		$multipler = $this -> hero -> level;

		$exp = $multipler * rand(12, 16);
		$gold = $multipler * rand(12, 16) * 10;

		$this -> database -> update('heroes', [
			'experience[+]' => $exp,
			'gold[+]' => $gold,
			'dungeon[+]' => 1,
			'killed_monsters[+]' => 1,
		],[
			'id' => $this -> hero -> id,
		]);


		$this -> result['exp'] = $exp;
		$this -> result['gold'] = $this -> convertGold($gold);
	}

	function winPlayer($gold){
		$this -> database -> update('heroes', [
			'gold[+]' => $gold,
			'beated_players[+]' => 1,
		],[
			'id' => $this -> hero -> id,
		]);
	}

	function winMonster(){
		$multipler = $this -> hero -> level;

		$exp = $multipler * rand(2, 5);
		$gold = $multipler * rand(2, 5);

		$this -> database -> update('heroes', [
			'experience[+]' => $exp,
			'gold[+]' => $gold,
			'killed_monsters[+]' => 1,
		],[
			'id' => $this -> hero -> id,
		]);

		$this -> result['exp'] = $exp;
		$this -> result['gold'] = $this -> convertGold($gold);
	}


	function getResult(){
		if($this -> hero -> health > 0) $this -> result[0] = true;
		else $this -> result[0] = false;

		if($this -> result[0]){
			if($this -> checkType() == 'dungeon') $this -> winDungeon();
			if($this -> checkType() == 'player') $this -> winPlayer();
			if($this -> checkType() == 'monster') $this -> winMonster();
		}
		$this -> hero -> levelUp();
		$this -> hero -> setDungeonTime();

		return $this -> result;
	}

	function startFight(){
		$this -> hitEnemy();
	}

	function hitEnemy() {

		$miss = rand(0, 100);
		if ($miss > $this -> enemy -> miss) {

			$critical = rand(0, 100);
			if ($critical < $this -> hero -> critical) $critical = 2;
			else $critical = 1;

			$hit = rand($this -> hero -> attack_min, $this -> hero -> attack_max) * $critical;
			$hit_def = floor($hit * ($this -> enemy -> defense / ($this -> hero -> level / 5)) / 100);
			$hit -= $hit_def;
			if($hit <= 0) $hit = 1;
			$this -> enemy -> health -= $hit;
		}

		else {
			$hit = 0;
		}

		$this -> result[] = $hit;

		if($this -> enemy -> health > 0) $this -> hitHero();
	}

	function hitHero() {

		$miss = rand(0, 100);
		if ($miss > $this -> hero -> miss) {

			$critical = rand(0, 100);
			if ($critical < $this -> enemy -> critical) $critical = 2;
			else $critical = 1;

			$hit = rand($this -> enemy -> attack_min, $this -> enemy -> attack_max) * $critical;
			$hit_def = floor($hit * ($this -> hero -> defense / ($this -> enemy -> id / 5)) / 100);
			$hit -= $hit_def;

			if($hit <= 0) $hit = 1;
 			$this -> hero -> health -= $hit;
		}

		else {
			$hit = 0;
		}

		$this -> result[] = $hit;

		if($this -> hero -> health > 0) $this -> hitEnemy();
	}

	function convertGold($gold){
    $money['bronze'] = $gold % 100;
    $money['silver'] = substr($gold % 10000 - $gold % 100, 0, -2);
    $money['gold'] = substr($gold % 1000000 - $gold % 10000, 0, -4);

    return $money;
  }
}
