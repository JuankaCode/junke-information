<?php 
require_once('../vendor/autoload.php');

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => getenv('DB_HOST'),
    'database'  => getenv('DB_NAME'),
    'username'  => getenv('DB_USER'),
    'password'  => getenv('DB_PASS'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
  $_SERVER,
  $_GET,
  $_POST,
  $_COOKIE,
  $_FILES
);

$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();

// methods get
$map->get('index', '/', [
  'controller'=> 'App\Controllers\IndexController',
  'action'=> 'indexAction'
]);
$map->get('addJob', '/job/add', [
  'controller'=>'App\Controllers\JobsController',
  'action'=> 'jobsAction',
  'auth'=>true,
]);
$map->get('addProject','/project/add',[
  'controller'=>'App\Controllers\ProjectsController',
  'action'=> 'projectAction',
  'auth'=>true,
]);
$map->get('addUser','/user/add',[
  'controller'=>'App\Controllers\UsersController',
  'action'=> 'usersAction'
]);
$map->get('loginForm','/login',[
  'controller'=>'App\Controllers\LoginController',
  'action'=> 'loginAction'
]);
$map->get('admin','/admin',[
  'controller'=>'App\Controllers\AdminController',
  'action'=> 'adminAction',
  'auth'=>true,
]);
$map->get('logout','/logout',[
  'controller'=>'App\Controllers\LoginController',
  'action'=> 'logout',
]);

// methods post
$map->post('postJob', '/job/add', [
  'controller'=>'App\Controllers\JobsController',
  'action'=> 'jobsAction'
]);
$map->post('postProject', '/project/add', [
  'controller'=>'App\Controllers\ProjectsController',
  'action'=> 'projectAction'
]);
$map->post('postUser', '/user/add', [
  'controller'=>'App\Controllers\UsersController',
  'action'=> 'usersAction'
]);
$map->post('postLogin', '/auth', [
  'controller'=>'App\Controllers\LoginController',
  'action'=> 'postLogin'
]);

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if(!$route){
  echo 'Error 404';
} else{
  $handleData = $route->handler;
  $controller = new $handleData['controller'];
  $actionName= $handleData['action'];
  $needsAuth = $handleData['auth'] ?? false;

  $sessionUserID = $_SESSION['userId'] ?? null;

  $response = $controller->$actionName($request);

  if($needsAuth && !$sessionUserID){
    $response = new Zend\Diactoros\Response\RedirectResponse('/login');  
  }

  foreach($response->getHeaders() as $name=>$values){
    foreach($values as $value){
      header(sprintf("%s: %s", $name, $value),false);
    }
  }
  http_response_code($response->getStatusCode());

  echo $response->getBody();
} 
// var_dump($route);
// var_dump($request->getUri()->getPath());
