<?php

namespace App\Controllers;

use \Core\View;
use \App\Flash;
use \App\Models\User;


class Profile extends \Core\Controller {

  public function ownAction() {
    $this->before();
        View::renderTemplate('Profile/view.html');
  }

  public function anotherAction() {
    $this->before();
    View::renderTemplate('Profile/view.html');
  }
}