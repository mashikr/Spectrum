<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

/**
 * Signup controller
 *
 * PHP version 7.0
 */
class Signup extends \Core\Controller
{
  /**
   * Show the signup page
   *
   * @return void
   */
  public function newAction() {
      View::renderTemplate('Signup/new.html',[
        'page' => 'signup'
    ]);
  }

  public function createAction() {
      $newUser = new User($_POST);
      
      if ($newUser->create()) {
        $this->redirect('/signup/success');
      } else {
        View::renderTemplate('Signup/new.html', [
          'user' => $newUser,
          'page' => 'signup'
        ]);
      }
  }

  public function successAction() {
    View::renderTemplate('Signup/success.html');
  }

  public function activeAction() {
    $token = $this->route_params['token'];
    if (User::activeAccount($token)) {
      View::renderTemplate('Signup/activateSuccess.html');
    } else {
      View::renderTemplate('Signup/activateError.html');
    }
  }
}
