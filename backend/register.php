<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "db_functions.php";

function mailSend($twig, $email, $subject, $template, $alt, $login, $token){
	$mail = new PHPMailer();

	try {
    //Server settings
    $mail -> isSMTP();
    $mail -> Host = 'smtp.gmail.com';
    $mail -> SMTPAuth = true;
    $mail -> Username = 'testofthegame12321@gmail.com';
    $mail -> Password = 'Ozi2015game';
		$mail -> SMTPSecure = 'tls';
    $mail -> Port = 587;
		$mail -> CharSet = 'UTF-8';

    //Recipients
    $mail -> setFrom('testofthegame12321@gmail.com', 'Mailer');
    $mail -> addAddress($email);

    //Content
    $mail -> isHTML(true);
    $mail -> Subject = $subject;
    $mail -> Body    = $twig -> render($template, array(
			'login' => $login,
			'token' => $token,
		));
    $mail -> AltBody = $alt;

    $mail -> send();
		return true;
	}
	catch (Exception $e) {
		return false;
	}
}

function checkReg(){
	$login = htmlentities(trim($_POST['login']), ENT_QUOTES, "UTF-8");
	$email = htmlentities(trim($_POST['email']), ENT_QUOTES, "UTF-8");
	$haslo = htmlentities(trim($_POST['haslo']), ENT_QUOTES, "UTF-8");
	$vhaslo = htmlentities(trim($_POST['vhaslo']), ENT_QUOTES, "UTF-8");
	$ip = ip2long($_SERVER['REMOTE_ADDR']);
	$date = date("Y-m-d");

	$spr1 = pobierz_wartosc("login", "users", "login = ?", $login);
	$spr2 = pobierz_wartosc("email", "users", "email = ?", $email);

	$spr3 = strlen($login);
	$spr4 = strlen($haslo);
	$spr5 = strlen($email);

	$komunikaty = '';

	$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

	if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email)) $komunikaty .= "Email jest niepoprawny!";

	if ($spr1[0]) $komunikaty .= "Ten login jest już zajęty!";
	if ($spr2[0]) $komunikaty .= "Ten email jest już zajęty!";
	if ($spr3 < 4) $komunikaty .= "Login musi mieć przynajmniej 5 znaków!";
	if ($spr3 > 14) $komunikaty .= "Login nie może mieć więcej niż 14 znaków!";
	if ($spr4 < 4) $komunikaty .= "Hasło musi mieć przynajmniej 5 znaków!";
	if ($spr4 >  20) $komunikaty .= "Hasło nie może mieć więcej niż 20 znaków!";
	if ($spr5 >  50) $komunikaty .= "Email nie może mieć więcej niż 50 znaków!";
	if ($haslo != $vhaslo) $komunikaty .= "Hasła się nie zgadzają!";
	if (
		preg_match('@[ęóąśłżźćńĘÓĄŚŁŻŹĆŃ]@', $login) 	||
		preg_match('@[ęóąśłżźćńĘÓĄŚŁŻŹĆŃ]@', $haslo)	||
		preg_match('@[ęóąśłżźćńĘÓĄŚŁŻŹĆŃ]@', $vhaslo)) $komunikaty .= "Nie możesz używać polskich znaków";
	if (preg_match('/[\!\@\#\$\%\^\&\*]/', $login)) $komunikaty .= "Login nie może zawierać znaków specjalnych!";
	if (!$login || !$haslo || !$vhaslo ) $komunikaty = "Musisz wypełnić wszystkie pola!";

	if($komunikaty){
		db_disconnect();
		return $komunikaty;
	}
	else return false;
}

function register($twig){
	require_once 'db_functions.php';

	if (isset($_POST['displaytime']) ) {
		if ($_POST['displaytime'] > 3) {
			db_disconnect();
			$_SESSION['error'] = "Przepraszamy, w twoim działaniu wykryto SPAM!";
			return false;
		}

		else{
			$login = htmlentities(trim($_POST['login']), ENT_QUOTES, "UTF-8");
			$email = htmlentities(trim($_POST['email']), ENT_QUOTES, "UTF-8");
			$haslo = htmlentities(trim($_POST['haslo']), ENT_QUOTES, "UTF-8");
			$vhaslo = htmlentities(trim($_POST['vhaslo']), ENT_QUOTES, "UTF-8");
			$name = htmlentities(trim($_POST['name']), ENT_QUOTES, "UTF-8");
			$ip = ip2long($_SERVER['REMOTE_ADDR']);
			$date = date("Y-m-d");

			$spr1 = pobierz_wartosc("login", "users", "login = ?", $login);
			$spr2 = pobierz_wartosc("email", "users", "email = ?", $email);
			$spr6 = pobierz_wartosc("name", "heroes", "name = ?", $name);

			$spr3 = strlen($login);
			$spr4 = strlen($haslo);
			$spr5 = strlen($email);

			$komunikaty = '';

			$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

			if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email)) $komunikaty .= "Email jest niepoprawny!";

			if ($spr1[0]) $komunikaty .= "Ten login jest już zajęty!";
			if ($spr2[0]) $komunikaty .= "Ten email jest już zajęty!";
			if ($spr6[0]) $komunikaty .= "Nazwa postaci jest niedostępna!";
			if ($spr3 < 4) $komunikaty .= "Login musi mieć przynajmniej 5 znaków!";
			if ($spr3 > 14) $komunikaty .= "Login nie może mieć więcej niż 14 znaków!";
			if ($spr4 < 4) $komunikaty .= "Hasło musi mieć przynajmniej 5 znaków!";
			if ($spr4 >  20) $komunikaty .= "Hasło nie może mieć więcej niż 20 znaków!";
			if ($spr5 >  50) $komunikaty .= "Email nie może mieć więcej niż 50 znaków!";
			if ($haslo != $vhaslo) $komunikaty .= "Hasła się nie zgadzają!";
			if (
				preg_match('@[ęóąśłżźćńĘÓĄŚŁŻŹĆŃ]@', $login) 	||
				preg_match('@[ęóąśłżźćńĘÓĄŚŁŻŹĆŃ]@', $haslo)	||
				preg_match('@[ęóąśłżźćńĘÓĄŚŁŻŹĆŃ]@', $vhaslo)) $komunikaty .= "Nie możesz używać polskich znaków";
			if (preg_match('/[\!\@\#\$\%\^\&\*]/', $login)) $komunikaty .= "Login nie może zawierać znaków specjalnych!";
			if (!$login || !$haslo || !$vhaslo ) $komunikaty = "Musisz wypełnić wszystkie pola!";

			if ($komunikaty){
				db_disconnect();
				$_SESSION['error'] = $komunikaty;
				return false;
			}

			else{
				$token = sha1(uniqid());

				if(mailSend($twig, $email, 'Rejestracja na portalu The Game', 'register_confirm.html.twig', 'Alternative text', $login, $token)){
					$haslo = password_hash($haslo, PASSWORD_BCRYPT, ["cost" => 10]);

					$class = htmlentities(trim($_POST['class']), ENT_QUOTES, "UTF-8");
					$race = htmlentities(trim($_POST['race']), ENT_QUOTES, "UTF-8");
					$strength = htmlentities(trim($_POST['strength']), ENT_QUOTES, "UTF-8") + 1;
					$vitality = htmlentities(trim($_POST['vitality']), ENT_QUOTES, "UTF-8") + 1;
					$agility = htmlentities(trim($_POST['agility']), ENT_QUOTES, "UTF-8") + 1;
					$intelligence = htmlentities(trim($_POST['intelligence']), ENT_QUOTES, "UTF-8") + 1;

					dodaj_wartosc("name, class, race, strength, vitality, agility, intelligence", "heroes", $name, $class, $race, $strength, $vitality, $agility, $intelligence);

					$hero_id = pobierz_wartosc("ID", "heroes", "name = ?", $name);

					dodaj_wartosc("login, password, email, token, date, ip, hero_id", "users", $login, $haslo, $email, $token, $date, $ip, $hero_id);

					db_disconnect();
					$_SESSION['success'] = "Gratulacje! Zostałeś zarejestrowany jako ".$login."! Aby zalogować się, aktywuj konto poprzez link wysłany na twój adres email.";
					return true;
				}
				else return false;
			}
		}
	}
}

function checkHero(){
	$user = pobierz_wartosc("name", "heroes", "name = ?", $_POST['name']);
	if($user == $_POST['name']) return true;
	else return false;
}
