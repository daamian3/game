<?php

use Medoo\Medoo;

class Enemy{
	function __construct($id){
		$this -> database = new Medoo([
      'database_type' => 'mysql',
      'database_name' => 'game',
      'server' => 'localhost',
      'username' => 'root',
      'password' => '',
      "charset" => "utf8",
		]);

		$check = $this -> database -> count('monsters', [
			'id' => $id,
		]);

		if($check > 0){
			$this -> id = $id;

			$stats = $this -> database -> get('monsters', [
	      'name',
	      'health',
	      'attack_min',
				'attack_max',
				'defense',
				'attack_min',
				'critical',
				'miss',
				'img',
	    ],[
	      'id' =>  $this -> id,
	    ]);

			$this -> name = $stats['name'];
			$this -> health = $stats['health'];
			$this -> attack_min = $stats['attack_min'];
			$this -> attack_max = $stats['attack_max'];
			$this -> defense = $stats['defense'];
			$this -> critical = $stats['critical'];
			$this -> miss = $stats['miss'];
			$this -> img = $stats['img'];
		}
		else die('Przeciwnik nie istnieje!');
	}

  function getStats(){
    return $this;
  }
}
