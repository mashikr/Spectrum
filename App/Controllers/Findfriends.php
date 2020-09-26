<?php

namespace App\Controllers;

use \Core\View;

class Findfriends extends \Core\Controller {

    public function responseAction() {
        $this->before();
        View::renderTemplate('Findfriends/response.html');
    }
}

?>