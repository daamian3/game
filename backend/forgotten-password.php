<?php
	session_start();

	require "db_functions.php";

	if (isset($_POST['display-time']) ) {

		if ($_POST['display-time'] < 1) {

			db_disconnect();
			$_SESSION['error'] = "Przepraszamy, w twoim działaniu wykryto SPAM!";
			header($index);
			exit();

		}
	}

	if(isset($_GET['key'])) $key =  htmlentities(trim($_GET['key']), ENT_QUOTES, "UTF-8");
	if(isset($_GET['login'])) $login =  htmlentities(trim($_GET['login']), ENT_QUOTES, "UTF-8");
	if(isset($key) && isset($login)) {

		$check = pobierz_wartosc("token", "users", "login = ?", $login);

		if($check==$key) {

			db_disconnect();
			$_SESSION['forgot'] = true;
			$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
			$_SESSION['login'] = $login;
			header($index);
			exit();

		}
		else{

			db_disconnect();
			$_SESSION['error'] = "Nieprawidłowy token!";
			header($index);
			exit();

		}
	}
	if(isset($_POST['nowe'])) $haslo =  trim($_POST['nowe']);
	if(isset($_POST['vnowe'])) $vhaslo =  trim($_POST['vnowe']);
	if(isset($_SESSION['login'])) $login =  trim($_SESSION['login']);

	if(isset($haslo) && isset($vhaslo) && isset($login)) {

		$spr1 = strlen($haslo);

		$komunikaty = '';

		if (!$haslo || !$vhaslo ) $komunikaty .= "Musisz wypełnić wszystkie pola!<br />";
		if ($spr1 < 4) $komunikaty .= "Hasło musi mieć przynajmniej 5 znaków!<br />";
		if ($spr4 >  20) $komunikaty .= "Hasło nie może mieć więcej niż 20 znaków!<br />";
		if ($haslo != $vhaslo) $komunikaty .= "Hasła się nie zgadzają!<br />";
		if (
		preg_match('@[ęóąśłżźćńĘÓĄŚŁŻŹĆŃ]@', $haslo)	||
		preg_match('@[ęóąśłżźćńĘÓĄŚŁŻŹĆŃ]@', $vhaslo)) $komunikaty .= "Nie możesz używać polskich znaków<br />";

		if ($komunikaty){

			db_disconnect();
			$_SESSION['error'] = $komunikaty;
			header($index);
			exit();

		}

		$haslo = password_hash($haslo, PASSWORD_DEFAULT);
		try {

			ustal_wartosc("password", "?", "users", "login = ?", $haslo, $login);
			ustal_wartosc("token", "0", "users", "login = ?", $login);

		}
		catch (Exception $err) {

			$err -> getMessage();
			db_disconnect();
			$_SESSION['error'] = "Przepraszamy, wystąpił błąd!";
			header($index);
			exit();

		}
		db_disconnect();
		$_SESSION['error'] = "Udało się! Hasło zostało zmienione.";
		header($index);
		exit();

	}
	else{

		$login = trim($_POST['login_email']);
		$email = filter_var($login, FILTER_SANITIZE_EMAIL);

		if ((filter_var($email, FILTER_VALIDATE_EMAIL)==true) || ($email!=$login)) $login = pobierz_wartosc("login", "users", "email = ?", $email);
		else $email = pobierz_wartosc("email", "users", "login = ?", $login);

		$ile = pobierz_wartosc("COUNT(*)", "users", "login = ?", $login);

		if($ile[0]==0){

			db_disconnect();
			$_SESSION['error'] = "Podany login lub email nie istnieje!";
			header($index);
			exit();

		}

		$token = sha1(uniqid());
		$temat = 'Prośba o przypomnienie hasła';
		$tekst = 'Witaj '.$login.', aby wygenerować nowe hasło przejdź w podany adres:\n http://starozytnosc-grecja.hol.es/alcomparison/newpass.php?key='.$token.'&login='.$login.'';
		$tekst = wordwrap($tekst,70);
		$tekst = str_replace('\n', '', $tekst);
		$mail = mail($email, $temat, $tekst);

		if($mail){

			ustal_wartosc("token", "?", "users", "login = ?", $token, $login);

			db_disconnect();
			$_SESSION['error'] = "Link do zmiany hasła został wysłany na twój email.";
			header($index);
			exit();

		}
	}
