<?php

namespace App\Controllers;

use \Core\View;

class Messages extends \Core\Controller {

    public function transferAction() {
        $this->before();
        View::renderTemplate('Messages/view.html', [
            'page' => 'messages'
        ]);
    }

    public function chatAction() {
        $this->before();
         View::renderTemplate('Messages/chat.html', [
            'page' => 'messages'
        ]);
     }
}

?>