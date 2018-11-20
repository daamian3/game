<?php

$loader = require_once 'vendor/autoload.php';
$profiler = new \Fabfuel\Prophiler\Profiler();
$profiler->addAggregator(new \Fabfuel\Prophiler\Aggregator\Database\QueryAggregator());
$profiler->addAggregator(new \Fabfuel\Prophiler\Aggregator\Cache\CacheAggregator());

require_once 'backend/Session.php';
require_once 'backend/Security.php';
require_once 'backend/Hero.php';
require_once 'backend/Enemy.php';
require_once 'backend/Fight.php';
require_once 'backend/Adventure.php';
require_once 'backend/Shop.php';
require_once 'backend/Dungeon.php';

$db = new \PDO('mysql:host=localhost;dbname=game;charset=utf8', 'root', '');
$auth = new \Delight\Auth\Auth($db);

$klein = new \Klein\Klein();

$klein -> respond(function ($request, $response, $service, $app) use ($klein) {
  $app -> register('twig', function () {

    $loader = new Twig_Loader_Filesystem('templates');
    $twig = new Twig_Environment($loader);
    $twig -> addGlobal('session', $_SESSION);

    return $twig;
  });
});

function indexAction($request, $response, $service, $app){

  global $auth;

  if($auth -> isLoggedIn()) {
    $hero = new Hero();

    echo $app -> twig -> render('hero.html.twig', array(
      'hero' => $hero -> getStats(),
      'eq' => $hero -> getEq(),
      'items' => $hero -> getBag(),
    ));
  }
  else echo $app -> twig -> render('creator.html.twig');
}

$klein -> respond('GET', '/game/', function ($request, $response, $service, $app) {
  indexAction($request, $response, $service, $app);
});

$klein -> respond('GET', '/game/hero', function ($request, $response, $service, $app) {
  indexAction($request, $response, $service, $app);
});

$klein -> respond('GET', '/game/shop', function ($request, $response, $service, $app) {
  global $auth;

  if($auth -> isLoggedIn()) {
    $hero = new Hero;
    $shop = new Shop($hero);

    echo $app -> twig -> render('shop.html.twig', array(
      'items' => $shop -> getItems(),
    ));
  }
  else header("Refresh:0; url=/game/");
});

$klein -> respond(array('GET', 'POST'), '/game/adventure', function ($request, $response, $service, $app) {
  global $auth;

  if($auth -> isLoggedIn()) {
    $hero = new Hero();
    $adventure = new Adventure($hero);

    if($adventure -> isStarted()){
      echo $app -> twig -> render('adventure.html.twig', array(
        'adventure' => $adventure -> getAdventure(),
      ));
    }

    else if(isset($_POST['adventure'])){
      $adventure -> start($_POST['adventure']);
      echo $app -> twig -> render('adventure.html.twig', array(
        'adventure' => $adventure -> getAdventure(),
      ));
    }

    else echo $app -> twig -> render('adventure.html.twig', array(
      'adventures' => $adventure -> getAdventures(),
    ));
  }
  else header("Refresh:0; url=/game/");
});

$klein -> respond('GET', '/game/dungeons', function ($request, $response, $service, $app) {

  global $auth;

  if($auth -> isLoggedIn()) {
    $hero = new Hero();
    $dungeon = new Dungeon($hero);

    if(!isset($_GET['name'])) echo $app -> twig -> render('dungeons.html.twig', array(
      'dungeon' => $dungeon -> getDungeon(),
    ));

    else{
      switch($_GET['name']){
        case 'basements':
          $name = 'Piwnice';
          break;
        case 'catacombs':
          $name = 'Katakumby';
          break;
        case 'snakesground':
          $name = 'Wężowe pole';
          break;
        case 'infernalcave':
          $name = 'Piekielna grota';
          break;
        case 'spiderlair':
          $name = 'Leże pająków';
          break;
        case 'demontower':
          $name = 'Wieża demonów';
          break;
        default:
          echo $app -> twig -> render('dungeons.html.twig');
          exit();
          break;
      }

      $enemy = new Enemy($dungeon -> getEnemyId());

      echo $app -> twig -> render('fight.html.twig', array(
        'dungeon' => $name,
        'hero' => $hero -> getStats(),
        'enemy' => $enemy -> getStats(),
        'is_available' => $hero -> dungeonIsAvailable(),
      ));
    }
  }

  else header("Refresh:0; url=/game/");
});

$klein -> respond('GET', '/game/login', function ($request, $response, $service, $app) {
  global $auth;

  if($auth -> isLoggedIn()) indexAction($request, $response, $service, $app);
  else echo $app -> twig -> render('logowanie.html.twig');
});

$klein -> respond(array('POST', 'GET'), '/game/register', function ($request, $response, $service, $app) {
  $security = new Security();
  echo $app -> twig -> render('creator.html.twig', array(
    'session' => $security -> register($app -> twig),
  ));
});

$klein -> respond('GET', '/game/verify_email', function ($request, $response, $service, $app) {
  $security = new Security();
  $security -> verifyEmail();
  indexAction($request, $response, $service, $app);
});

$klein -> respond('POST', '/game/fight', function ($request, $response, $service, $app) {
  $hero = new Hero();
  $dunegon = new Dungeon($hero);
  $enemy = new Enemy($dunegon -> getEnemyId());

  $fight = new Fight($hero, $enemy);

  echo json_encode($fight -> getResult());
});

$klein -> respond('POST', '/game/attrib_up', function ($request, $response, $service, $app) {
  $hero = new Hero();
  echo $hero -> attribUp($_POST['attrib']);
});

$klein -> respond('POST', '/game/attrib_cost', function ($request, $response, $service, $app) {
  $hero = new Hero();
  echo json_encode($hero -> getAttribCost($_POST['attrib']));
});

$klein -> respond('POST', '/game/get_money', function ($request, $response, $service, $app) {
  $hero = new Hero();
  echo json_encode($hero -> getMoney());
});

$klein -> respond('POST', '/game/check_hero', function ($request, $response, $service, $app) {
  $hero = new Hero();
  echo $hero -> checkHero();
});

$klein -> respond('POST', '/game/check_reg', function ($request, $response, $service, $app) {
  $security = new Security();
  echo $security -> isUserAvailable();
});

$klein -> respond('POST', '/game/hero_equip', function ($request, $response, $service, $app) {
  $hero = new Hero();
  echo $hero -> equipItem($_POST['item']);
});

$klein -> respond('POST', '/game/hero_unEquip', function ($request, $response, $service, $app) {
  $hero = new Hero();
  echo $hero -> unEquipItem($_POST['item']);
});

$klein -> respond('POST', '/game/buy_item', function ($request, $response, $service, $app) {
  $hero = new Hero;
  $shop = new Shop($hero);

  $result = $shop -> buyItem($_POST['id']);
  echo json_encode($result, JSON_UNESCAPED_UNICODE);
});

$klein -> respond('POST', '/game/get_stats', function ($request, $response, $service, $app) {
  $hero = new Hero();
  echo json_encode($hero -> getStats());
});

$klein -> respond('GET', '/game/hero__eq', function ($request, $response, $service, $app) {
  $hero = new Hero();
  echo $app -> twig -> render('hero__eq.html.twig', array(
    'eq' => $hero -> getEq(),
    'items' => $hero -> getBag(),
  ));
});

$klein -> respond('GET', '/game/hero__stats', function ($request, $response, $service, $app) {
  $hero = new Hero();
  echo $app -> twig -> render('hero__stats.html.twig', array(
    'hero' => $hero -> getStats(),
  ));
});

$klein -> respond('GET', '/game/hero__attrib', function ($request, $response, $service, $app) {
  $hero = new Hero();
  echo $app -> twig -> render('hero__attrib.html.twig', array(
    'hero' => $hero -> getStats(),
  ));
});

$klein -> respond(array('POST', 'GET'), '/game/password_reset', function ($request, $response, $service, $app) {
  global $auth;

  $security = new Security;

  if(isset($_POST['selector']) && isset($_POST['token']) && isset($_POST['password']) && isset($_POST['password-check'])){
    $security -> changePassword();
    echo $app -> twig -> render('logowanie.html.twig');
  }
  else if(isset($_GET['selector']) && isset($_GET['token']) && $security -> isPasswordResetCorrect()){
    echo $app -> twig -> render('password_change.html.twig', array(
      'selector' => $_GET['selector'],
      'token' => $_GET['token']
    ));
  }
  else{
    echo $app -> twig -> render('logowanie.html.twig', array(
      'session' => $security -> forgotPassword($app -> twig),
    ));
  }
});

$klein -> respond(array('POST', 'GET'), '/game/zaloguj', function ($request, $response, $service, $app) {
  global $auth;

  $security = new Security;

  if($auth -> isLoggedIn()) indexAction($request, $response, $service, $app);
  else if($security -> login()) indexAction($request, $response, $service, $app);
  else echo $app -> twig -> render('logowanie.html.twig');
});

$klein -> respond('GET', '/game/logout', function ($request, $response, $service, $app) {
  global $auth;
  $auth -> logOut();
  $auth -> destroySession();

  indexAction($request, $response, $service, $app);
});

$klein -> dispatch();

unset($_SESSION['error']);
unset($_SESSION['success']);
unset($_SESSION['adventure']);

$toolbar = new \Fabfuel\Prophiler\Toolbar($profiler);
$toolbar->addDataCollector(new \Fabfuel\Prophiler\DataCollector\Request());
//echo $toolbar->render();
exit();
