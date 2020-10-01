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

    protected function logIn($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user']['email'] = $user->email;
        $_SESSION['user']['firstname'] = $user->firstname;
        $_SESSION['user']['lastname'] = $user->lastname;
        $_SESSION['user']['name'] = $user->firstname . " " . $user->lastname;
        $_SESSION['user']['dob'] = $user->dob;
        $_SESSION['user']['gender'] = $user->gender;
        $_SESSION['user']['profile_pic'] = $user->profile_pic;
        $_SESSION['user']['cover_pic'] = $user->cover_pic;
        $_SESSION['user']['works_at'] = $user->works_at;
        $_SESSION['user']['studied'] = $user->studied;
        $_SESSION['user']['live_in'] = $user->live_in;
        $_SESSION['user']['home_town'] = $user->home_town;
        $_SESSION['user']['phone'] = $user->phone;
    }

    protected function fileType($name, $type) {
        $type = explode("/",$type);
        $type2 = explode(".",$name);
        $type2 = end($type2);
    
        if ($type[0] == 'image') {
            $type = 'photo';
        } else if ($type2 == 'pdf') {
            $type = 'pdf';
        } else if ($type[0] == 'audio') {
            $type = 'audio';
        } else if ($type[0] == 'video') {
            $type = 'video';
        } else if ($type2 == 'doc' || $type2 == 'docx') {
            $type = 'doc';
        } else if ($type2 == 'ppt' || $type2 == 'pptx') {
            $type = 'ppt';
        }  else if ($type2 == 'xlsx') {
            $type = 'excel';
        }  else if ($type2 == 'txt') {
            $type = 'txt';
        } else {
            $type = 'invalid';
        }

        return $type;
    }

    protected function calcTime($time) {
        date_default_timezone_set("Asia/Dhaka");
        $time = time() - strtotime($time);

        if ($time/60 < 1) {
            return $time .' sec ago';
        }else if (floor($time/60) == 1) {
            return '1 min ago';
        }else if ($time/3600 < 1) {
            return floor($time/60) . ' min ago';
        } else if (floor($time/3600) == 1) {
            return '1 hour ago';
        } else if ($time/86400 < 1) {
            return floor($time/3600) . ' hour ago';
        } else if (floor($time/86400) == 1) {
            return '1 day ago';
        } else if($time/2592000 < 1){
            return floor($time/86400) . ' day ago';
        } else if (floor($time/2592000) == 1) {
            return '1 month ago';
        } else if($time/31104000 < 1) {
            return floor($time/2592000) . ' month ago';
        } else if (floor($time/31104000) == 1) {
            return '1 year ago';
        } else {
            return floor($time/31104000) . ' year ago';
        }
    }

    protected function calcTimeFormate($date) {
       
        $date = date_format(date_create($date),"D, d M, Y % g:i A");
        return str_replace("%",'at', $date);
    }

    protected function limitContent($content, $limit) {
        return implode(" ", array_slice(str_word_count($content, 1),0,$limit)) . "...";
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
            $user_id = $user['user_id'];
            if ($user) {
                $user = User::findByEmail($user['email']);
                $this->logIn($user);
            }          
        }

        return $user_id;
    }
}

?>