<?php

namespace Core;
use \App\Models\User;

abstract class Controller {
    protected $route_params = [];

    public function  __construct($route_params) {
        $this->route_params = $route_params;
        $this->userId();
    }

    public function __call($name, $args) {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            
            call_user_func_array([$this, $method], $args);
            //$this->after();
            
        } else {
            //echo "Method $method not found in controller " . get_class($this);
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }

    protected function before() {

        if (!$this->userId()) {
            $this->redirect('/');
        }
    }

    protected function after() {

    }

    public function redirect($url) {
        header('location: ' . \App\Config::FOLDER . $url, true, 303);
        exit;
    }

    public function userId() {
        $user_id = null;
        $cookie = $_COOKIE['spectrum_remember'] ?? false;
    
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        } elseif ($cookie) {
            $user = User::getUserByToken($cookie);
            $_SESSION['user_id'] = $user['user_id'];            
        }

        return $user_id;
    }
}

?>