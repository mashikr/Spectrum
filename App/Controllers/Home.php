<?php

namespace App\Controllers;

use \Core\View;

class Home extends \Core\Controller {

    public function indexAction() {
        if ($this->userId()) {
            $this->redirect('/home');
        }
        View::renderTemplate('Home/index.html');
    }

    public function userAction() {
        $this->before();
        View::renderTemplate('Home/user.html');
    }
}

?>