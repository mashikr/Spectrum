<?php
session_start();
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
$router->add('login', ['controller' => 'Login', 'action' => 'login']);
$router->add('logout', ['controller' => 'Login', 'action' => 'logout']);
$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);
$router->add('signup/active/{token:[\da-f]+}', ['controller' => 'Signup', 'action' => 'active']);
$router->add('{controller}/{action}');


$url = $_SERVER['QUERY_STRING'];

$router->dispatch($url);

?>