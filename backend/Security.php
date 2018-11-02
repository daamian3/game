<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Security{

  function __construct(){
    $auth = new \Delight\Auth\Auth($db);
    $db = new \PDO('mysql:host=localhost;dbname=game;charset=utf8', 'root', '');

    $this -> database = new Medoo([
      'database_type' => 'mysql',
      'database_name' => 'game',
      'server' => 'localhost',
      'username' => 'root',
      'password' => '',
      "charset" => "utf8",
		]);
  }

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
  			'selector' => $selector,
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

  function login(){
    try {
      $auth -> loginWithUsername($_POST['username'], $_POST['password']);
      echo 'User is logged in';
    }

    catch (\Delight\Auth\UnknownUsernameException $e) {
      die('Wrong login');
    }

    catch (\Delight\Auth\AmbiguousUsernameException $e) {
      die('Ambiguous username.');
    }

    catch (\Delight\Auth\InvalidPasswordException $e) {
      die('Wrong password');
    }

    catch (\Delight\Auth\EmailNotVerifiedException $e) {
      die('Email not verified');
    }

    catch (\Delight\Auth\TooManyRequestsException $e) {
      die('Too many requests');
    }
  }

  function register($twig){
    try {
      $userId = $auth -> registerWithUniqueUsername($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {
        if(mailSend($twig, $_POST['email'], 'Rejestracja na portalu The Game', 'register_confirm.html.twig', 'Alternative text', $selector, $token)){

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
            "luck" => $luck,
          ]);

          $hero_id = $this -> database -> get('heroes', 'id', [
            'name' => $name,
          ]);

          $this -> database -> update('users', [
      			'hero_id' => $hero_id,
      		],[
      			'username' => $_POST['username'],
      		]);

          echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
        }

      });

      echo 'We have signed up a new user with the ID ' . $userId;
    }

    catch (\Delight\Auth\InvalidEmailException $e) {
      die('Invalid email address');
    }

    catch (\Delight\Auth\InvalidPasswordException $e) {
      die('Invalid password');
    }

    catch (\Delight\Auth\DuplicateUsernameException $e) {
      die('User already exists');
    }

    catch (\Delight\Auth\UserAlreadyExistsException $e) {
      die('User already exists');
    }

    catch (\Delight\Auth\TooManyRequestsException $e) {
      die('Too many requests');
    }
  }

  function verifyEmail(){
    try {
      $auth -> confirmEmailAndSignIn($_GET['selector'], $_GET['token']);

      echo 'Email address has been verified';
    }

    catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
      die('Invalid token');
    }

    catch (\Delight\Auth\TokenExpiredException $e) {
      die('Token expired');
    }

    catch (\Delight\Auth\UserAlreadyExistsException $e) {
      die('Email address already exists');
    }

    catch (\Delight\Auth\TooManyRequestsException $e) {
      die('Too many requests');
    }
  }

  function forgotPassword(){

    try {
      $auth->forgotPassword($_POST['email'], function ($selector, $token) {
          echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
      });
      $url = 'localhost/game/reset_password?selector=' . \urlencode($selector) . '&token=' . \urlencode($token);

      echo 'Request has been generated';
    }

    catch (\Delight\Auth\InvalidEmailException $e) {
      die('Invalid email address');
    }

    catch (\Delight\Auth\EmailNotVerifiedException $e) {
      die('Email not verified');
    }

    catch (\Delight\Auth\ResetDisabledException $e) {
      die('Password reset is disabled');
    }

    catch (\Delight\Auth\TooManyRequestsException $e) {
      die('Too many requests');
    }
  }
}