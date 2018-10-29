<?php
$loader = require_once 'vendor/autoload.php';
$profiler = new \Fabfuel\Prophiler\Profiler();

require_once 'backend/session.php';
require_once 'backend/login.php';
require_once 'backend/register.php';
require_once 'backend/hero.php';
require_once 'backend/enemy.php';
require_once 'backend/fight.php';
require_once 'backend/adventure.php';
require_once 'backend/shop.php';

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

  if(zalogowany()){
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
  if(zalogowany()){
    $hero = new Hero;
    $shop = new Shop($hero);

    echo $app -> twig -> render('shop.html.twig', array(
      'items' => $shop -> getItems(),
    ));
  }
  else header("Refresh:0; url=/game/");
});

$klein -> respond('GET', '/game/adventure', function ($request, $response, $service, $app) {
  if(zalogowany()){
    $adventure = (array) new Adventure(new Hero);

    echo $app -> twig -> render('adventure.html.twig', array(
      'adventures' => $adventure,
    ));
  }
  else header("Refresh:0; url=/game/");
});

$klein -> respond('GET', '/game/dungeons', function ($request, $response, $service, $app) {

  if(zalogowany()){
    if(!isset($_GET['name'])) echo $app -> twig -> render('dungeons.html.twig');

    else{
      switch($_GET['name']){
        case 'basements':
          $dungeon = 'Piwnice';
          break;
        case 'catacombs':
          $dungeon = 'Katakumby';
          break;
        case 'snakesground':
          $dungeon = 'Wężowe pole';
          break;
        case 'infernalcave':
          $dungeon = 'Piekielna grota';
          break;
        case 'spiderlair':
          $dungeon = 'Leże pająków';
          break;
        case 'demontower':
          $dungeon = 'Wieża demonów';
          break;
        default:
          echo $app -> twig -> render('dungeons.html.twig');
          exit();
          break;
      }
      $hero = new Hero();

      $id = pobierz_wartosc('dungeon', 'heroes', 'id = ?', $hero -> id);
      $enemy = new Enemy($id);

      echo $app -> twig -> render('fight.html.twig', array(
        'dungeon' => $dungeon,
        'hero' => $hero -> getStats(),
        'enemy' => $enemy -> getStats(),
        'is_available' => $hero -> dungeonIsAvailable(),
      ));
    }
  }

  else header("Refresh:0; url=/game/");
});

$klein -> respond('GET', '/game/login', function ($request, $response, $service, $app) {
  if(zalogowany()) indexAction($request, $response, $service, $app);
  else echo $app -> twig -> render('logowanie.html.twig');
});

$klein -> respond(array('POST', 'GET'), '/game/register', function ($request, $response, $service, $app) {
  register($app -> twig);
  echo $app -> twig -> render('creator.html.twig', array(
    'session' => $_SESSION,
  ));
});

$klein -> respond('POST', '/game/fight', function ($request, $response, $service, $app) {
  $hero = new Hero();

  $id = pobierz_wartosc('dungeon', 'heroes', 'id = ?', $hero -> id);
  $enemy = new Enemy($id);

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
  echo checkHero();
});

$klein -> respond('POST', '/game/check_reg', function ($request, $response, $service, $app) {
  echo checkReg();
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
  $hero -> getStats();
  echo json_encode(array(
    'health' => $hero -> getHealth(),
    'attack_min' => $hero -> attack_min,
    'attack_max' => $hero -> attack_max,
    'defense' => $hero -> defense,
    'critical' => $hero -> critical,
    'miss' => $hero -> miss,
    'vitality' => $hero -> vitality,
    'strength' => $hero -> strength,
    'intelligence' => $hero -> intelligence,
    'agility' => $hero -> agility,
    'luck' => $hero -> luck,
  ));
});

$klein -> respond(array('POST', 'GET'), '/game/zaloguj', function ($request, $response, $service, $app) {
  if(zalogowany()) indexAction($request, $response, $service, $app);
  else if(zaloguj()) indexAction($request, $response, $service, $app);
  else echo $app -> twig -> render('logowanie.html.twig');
});

$klein -> respond('GET', '/game/logout', function ($request, $response, $service, $app) {
  if(wyloguj()) header("Refresh:0; url=/game/");
  else{
    $_SESSION['error'] = 'Przepraszamy, wystąpił błąd!';
    header("Refresh:0; url=/game/");
  }
});

$klein -> dispatch();

unset($_SESSION['error']);
unset($_SESSION['success']);

$toolbar = new \Fabfuel\Prophiler\Toolbar($profiler);
$toolbar->addDataCollector(new \Fabfuel\Prophiler\DataCollector\Request());
//echo $toolbar->render();
exit();
