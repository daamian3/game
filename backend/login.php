<?php
	function zaloguj(){
		require_once "db_functions.php";

		$wrong_pass = "Nieprawidłowa nazwa użytkownika lub login!";
		$acces_denied = "Odmowa dostępu!";

		if(!isset($_POST['name']) || !isset($_POST['pass'])) return false;

		if(isset($_GET['name'])){
			$_SESSION['error'] = $acces_denied;
			return false;
		}

		if(isset($_GET['pass'])){
			$_SESSION['error'] = $acces_denied;
			return false;
		}

		$login = htmlentities(trim($_POST['name']), ENT_QUOTES, "UTF-8");
		$haslo = htmlentities(trim($_POST['pass']), ENT_QUOTES, "UTF-8");

		$pass = pobierz_wartosc("password", "users", "login = ?", $login);

		if(password_verify($haslo, $pass)) {

			$_SESSION['auth'] = true;
			$_SESSION['ip'] = crypt($_SERVER['REMOTE_ADDR'], uniqid());
			$_SESSION['success'] = 'Zostałeś zalogowany';
			$_SESSION['id'] = pobierz_wartosc('hero_id', 'users', 'login = ?', $login);
			return true;

		}

	  else{
	    $_SESSION['error'] = $wrong_pass;
			return false;
	  }
	}

	function wyloguj(){
	  if(session_destroy()) return true;
		else return false;
	}

	function zalogowany(){
		if(isset($_SESSION['auth']) && $_SESSION['auth'] == true && $_SESSION['ip'] == crypt($_SERVER['REMOTE_ADDR'], uniqid())) return true;
		else return false;
	}
