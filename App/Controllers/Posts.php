<?php

namespace App\Controllers;

use \Core\View;

class Posts extends \Core\Controller {

    public function addAction() {
        $this->before();
       if(isset($_POST)) {
           $post =  $_POST['post'];
           echo $post;
       }
    }
}

?>