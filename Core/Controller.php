<?php

namespace Core;
use \App\Models\User;

abstract class Controller {
    protected $route_params = [];

    public function  __construct($route_params) {
        $this->route_params = $route_params;
    }

    public function __call($name, $args) {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            //echo "Method $method not found in controller " . get_class($this);
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }

    protected function before() {

    }

    protected function after() {

    }

    public function redirect($url) {
        header('location: /login-mvc' . $url, true, 303);
        exit;
    }

    public static function username() {
        $name = '';
        $cookie = $_COOKIE['remember_me'] ?? false;
        if (isset($_SESSION['username'])) {
            $name = $_SESSION['username'];
        } elseif ($cookie) {
            $user = User::getUserByToken($cookie);
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            
            $name = $user['name'];
        }

        return $name;
    }
}

?>