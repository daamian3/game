<?php

class Enemy{
	function __construct($id){
    $this -> id = pobierz_wartosc('id', 'monsters', 'id = ?', $id);

		$stats = pobierz_wartosc('*', 'monsters', 'id = ?', $this -> id);
		$this -> name = $stats['name'];
		$this -> health = $stats['health'];
		$this -> attack_min = $stats['attack_min'];
		$this -> attack_max = $stats['attack_max'];
		$this -> defense = $stats['defense'];
		$this -> critical = $stats['critical'];
		$this -> miss = $stats['miss'];
		$this -> img = $stats['img'];
	}

  function getStats(){
    return $this;
  }
}
