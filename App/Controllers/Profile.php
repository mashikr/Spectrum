<?php

namespace App\Controllers;

use \Core\View;
use \App\Flash;
use \App\Models\User;


class Profile extends \Core\Controller {

  public function indexAction() {
    if (static::username()){
        View::renderTemplate('profile/view.html',[
            'id' => $_SESSION['user_id'],
            'email' => $_SESSION['email']
        ]);
    } else {
        $this->redirect('/');
    }
      
  }
}