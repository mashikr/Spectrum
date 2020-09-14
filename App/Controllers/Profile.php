<?php

namespace App\Controllers;

use \Core\View;
use \App\Flash;
use \App\Models\User;


class Profile extends \Core\Controller {

  public function viewAction() {
    if (static::username()){
        View::renderTemplate('profile/view.html',[
            'page' => 'profile',
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['username'],
            'email' => $_SESSION['email']
        ]);
    } else {
        Flash::addMessage('You\'r not logged in', 'error');
        $this->redirect('/');
    }
      
  }
}