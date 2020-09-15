<?php

namespace App\Controllers;

use \Core\View;

class Home extends \Core\Controller {

    public function indexAction() {
        if (isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
            $this->redirect('/profile');
        }
        View::renderTemplate('Home/index.html');
    }
}

?>