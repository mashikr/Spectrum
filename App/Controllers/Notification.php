<?php

namespace App\Controllers;

use \Core\View;

class Notification extends \Core\Controller {

    public function viewAction() {
        $this->before();
        View::renderTemplate('Notification/view.html');
      
    }
}

?>