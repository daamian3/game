<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Medoo\Medoo;

class Security{

  function __construct(){
    $db = new \PDO('mysql:host=localhost;dbname=game;charset=utf8', 'root', '');
    $this -> auth = new \Delight\Auth\Auth($db);

    $this -> database = new Medoo([
      'database_type' => 'mysql',
      'database_name' => 'game',
      'server' => 'localhost',
      'username' => 'root',
      'password' => '',
      "charset" => "utf8",
		]);
  }

  function mailSend($email, $subject, $template, $alt, $selector, $token){
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
      $mail -> Body    = $this -> twig -> render($template, array(
  			'selector' => $selector,
  			'token' => $token,
  		));
      $mail -> AltBody = $alt;

      $mail -> send();
  		return true;
  	}
  	catch (Exception $e) {
    	echo $e;
  	}
  }

  function login(){
    try {
      if(isset($_POST['username']) && isset($_POST['password'])) {
        $this -> auth -> loginWithUsername($_POST['username'], $_POST['password']);
        $_SESSION['auth'] = true;
        return true;
      }
    }

    catch (\Delight\Auth\UnknownUsernameException $e) {
      $_SESSION['error'] = 'Nieprawidłowa nazwa użytkownika lub hasło';
      error_log($e, 0);
    }

    catch (\Delight\Auth\AmbiguousUsernameException $e) {
      $_SESSION['error'] = 'Przepraszamy, wystąpił błąd';
      error_log($e, 0);
    }

    catch (\Delight\Auth\InvalidPasswordException $e) {
      $_SESSION['error'] = 'Nieprawidłowa nazwa użytkownika lub hasło';
      error_log($e, 0);
    }

    catch (\Delight\Auth\EmailNotVerifiedException $e) {
      $_SESSION['error'] = 'Konto nie zostało aktywowane';
      error_log($e, 0);
    }

    catch (\Delight\Auth\TooManyRequestsException $e) {
      $_SESSION['error'] = 'Zbyt wiele prób, dostęp zostanie czasowo wstrzymany';
      error_log($e, 0);
    }

    return false;
  }

  function register($twig){
    $this -> twig = $twig;
    try {
      $userId = $this -> auth -> registerWithUniqueUsername($_POST['email'], $_POST['haslo'], $_POST['login'], function ($selector, $token) {
        if($this -> mailSend($_POST['email'], 'Rejestracja na portalu The Game', 'register_confirm.html.twig', 'Alternative text', $selector, $token)){

          $name = htmlentities(trim($_POST['name']), ENT_QUOTES, "UTF-8");
          $class = htmlentities(trim($_POST['class']), ENT_QUOTES, "UTF-8");
					$race = htmlentities(trim($_POST['race']), ENT_QUOTES, "UTF-8");
					$strength = htmlentities(trim($_POST['strength']), ENT_QUOTES, "UTF-8") + 1;
					$vitality = htmlentities(trim($_POST['vitality']), ENT_QUOTES, "UTF-8") + 1;
					$agility = htmlentities(trim($_POST['agility']), ENT_QUOTES, "UTF-8") + 1;
					$intelligence = htmlentities(trim($_POST['intelligence']), ENT_QUOTES, "UTF-8") + 1;

          $this -> database -> insert("heroes", [
          	"name" => $name,
            "class" => $class,
            "race" => $race,
            "vitality" => $vitality,
            "strength" => $strength,
            "intelligence" => $intelligence,
            "agility" => $agility,
            "luck" => 1,
          ]);

          $hero_id = $this -> database -> get('heroes', 'id', [
            'name' => $name,
          ]);

          $this -> database -> update('users', [
      			'hero_id' => $hero_id,
      		],[
      			'username' => $_POST['login'],
      		]);

          echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
        }

      });

      echo 'Zostałeś zarejestrowany jako: '.$_POST['login'];
    }

    catch (\Delight\Auth\InvalidEmailException $e) {
      $_SESSION['error'] = 'Niedozwolony adres email';
      error_log($e, 0);
    }

    catch (\Delight\Auth\InvalidPasswordException $e) {
      $_SESSION['error'] = 'Niedozwolone hasło';
      error_log($e, 0);
    }

    catch (\Delight\Auth\DuplicateUsernameException $e) {
      $_SESSION['error'] = 'Taki użytkownik już istnieje';
      error_log($e, 0);
    }

    catch (\Delight\Auth\UserAlreadyExistsException $e) {
      $_SESSION['error'] = 'Taki użytkownik już istnieje';
      error_log($e, 0);
    }

    catch (\Delight\Auth\TooManyRequestsException $e) {
      $_SESSION['error'] = 'Zbyt wiele prób, dostęp zostanie czasowo wstrzymany';
      error_log($e, 0);
    }
  }

  function isUserAvailable(){
    return $this -> database -> count('users', [
      'username' => $_POST['username'],
      'email' => $_POST['email'],
    ]);
  }

  function verifyEmail(){
    try {
      $this -> auth -> confirmEmailAndSignIn($_GET['selector'], $_GET['token']);
      $_SESSION['success'] = 'Konto zostało aktywowane';
    }

    catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
      $_SESSION['error'] = 'Ups, coś poszło nie tak - nieprawidłowy link';
      error_log($e, 0);
    }

    catch (\Delight\Auth\TokenExpiredException $e) {
      $_SESSION['error'] = 'Ups, coś poszło nie tak - link stracił ważność';
      error_log($e, 0);
    }

    catch (\Delight\Auth\UserAlreadyExistsException $e) {
      $_SESSION['error'] = 'Ups, coś poszło nie tak - konto jest już aktywne';
      error_log($e, 0);
    }

    catch (\Delight\Auth\TooManyRequestsException $e) {
      $_SESSION['error'] = 'Zbyt wiele prób, dostęp zostanie czasowo wstrzymany';
      error_log($e, 0);
    }
  }

  function forgotPassword($twig){
    $this -> twig = $twig;
    if(isset($_POST['email'])){
      try {
        $this -> auth -> forgotPassword($_POST['email'], function ($selector, $token) {
          if($this -> mailSend($_POST['email'], 'Rejestracja na portalu The Game', 'forgot_password.html.twig', 'Alternative text', $selector, $token)){
            $_SESSION['success'] = 'Link do zmiany hasła został wysłany';
            return true;
          }
        });
      }

      catch (\Delight\Auth\InvalidEmailException $e) {
        $_SESSION['error'] = 'Nieprawidłowy adres email';
        error_log($e, 0);
      }

      catch (\Delight\Auth\EmailNotVerifiedException $e) {
        $_SESSION['error'] = 'Konto jest nieaktywne';
        error_log($e, 0);
      }

      catch (\Delight\Auth\ResetDisabledException $e) {
        $_SESSION['error'] = 'Nie można było zmienić hasła';
        error_log($e, 0);
      }

      catch (\Delight\Auth\TooManyRequestsException $e) {
        $_SESSION['error'] = 'Zbyt wiele prób, dostęp zostanie czasowo wstrzymany';
        error_log($e, 0);
      }
    }
    else $_SESSION['error'] = 'Nieprawidłowy adres email';
  }

  function isPasswordResetCorrect(){
    try {
      $this -> auth -> canResetPasswordOrThrow($_GET['selector'], $_GET['token']);
      return true;
    }

    catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
      $_SESSION['error'] = 'Ups, coś poszło nie tak - nieprawidłowy link';
      error_log($e, 0);
    }

    catch (\Delight\Auth\TokenExpiredException $e) {
      $_SESSION['error'] = 'Ups, coś poszło nie tak - link stracił ważność';
      error_log($e, 0);
    }

    catch (\Delight\Auth\ResetDisabledException $e) {
      $_SESSION['error'] = 'Nie można było zmienić hasła';
      error_log($e, 0);
    }

    catch (\Delight\Auth\TooManyRequestsException $e) {
      $_SESSION['error'] = 'Zbyt wiele prób, dostęp zostanie czasowo wstrzymany';
      error_log($e, 0);
    }

    return false;
  }

  function changePassword(){
    try {
      if($_POST['password'] == $_POST['password-check']){
        $this -> auth -> resetPassword($_POST['selector'], $_POST['token'], $_POST['password']);
        $_SESSION['success'] = 'Hasło zostało zmienione';
      }
      else{
        $_SESSION['error'] = 'Hasła nie są identyczne';
      }
    }
    catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
      $_SESSION['error'] = 'Ups, coś poszło nie tak - nieprawidłowy link';
      error_log($e, 0);
    }

    catch (\Delight\Auth\TokenExpiredException $e) {
      $_SESSION['error'] = 'Ups, coś poszło nie tak - link stracił ważność';
      error_log($e, 0);
    }

    catch (\Delight\Auth\ResetDisabledException $e) {
      $_SESSION['error'] = 'Nie można było zmienić hasła';
      error_log($e, 0);
    }

    catch (\Delight\Auth\InvalidPasswordException $e) {
      $_SESSION['error'] = 'Niedozwolone hasło';
      error_log($e, 0);
    }

    catch (\Delight\Auth\TooManyRequestsException $e) {
      $_SESSION['error'] = 'Zbyt wiele prób, dostęp zostanie czasowo wstrzymany';
      error_log($e, 0);
    }
  }
}
