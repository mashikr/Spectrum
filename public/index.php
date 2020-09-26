<?php
session_start();
ob_start();
require '../vendor/autoload.php';

spl_autoload_register(function ($class) {
    $root = dirname(__DIR__);
    $file = $root . '/' . str_replace('\\', '/', $class) . '.php';
    if (is_readable($file)) {
        require $root . '/' . str_replace('\\', '/', $class) . '.php';
    }
});

error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

$router = new Core\Router();


// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('/', ['controller' => 'Home', 'action' => 'index']);
$router->add('home', ['controller' => 'Home', 'action' => 'user']);
$router->add('login', ['controller' => 'Home', 'action' => 'index']);
$router->add('signup', ['controller' => 'Signup', 'action' => 'new']);
$router->add('logout', ['controller' => 'Login', 'action' => 'logout']);
$router->add('profile', ['controller' => 'Profile', 'action' => 'own']);
$router->add('profile/', ['controller' => 'Profile', 'action' => 'own']);
$router->add('profile/{id:\d+}', ['controller' => 'Profile', 'action' => 'another']);
$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);
$router->add('signup/active/{token:[\da-f]+}', ['controller' => 'Signup', 'action' => 'active']);
$router->add('{controller}/{action}');
$router->add('messages', ['controller' => 'Messages', 'action' => 'transfer']);
$router->add('messages/', ['controller' => 'Messages', 'action' => 'transfer']);
$router->add('messages/{id:\d+}', ['controller' => 'Messages', 'action' => 'chat']);
$router->add('notification', ['controller' => 'Notification', 'action' => 'view']);
$router->add('notification/', ['controller' => 'Notification', 'action' => 'view']);
$router->add('findfriends', ['controller' => 'Findfriends', 'action' => 'response']);
$router->add('findfriends/', ['controller' => 'Findfriends', 'action' => 'response']);



$url = $_SERVER['QUERY_STRING'];

$router->dispatch($url);

?>