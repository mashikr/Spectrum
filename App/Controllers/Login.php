<?php

namespace App\Controllers;

use \Core\View;
use \App\Flash;
use \App\Models\User;


class Login extends \Core\Controller {

  public function newAction() {
      $user = User::authenticate($_POST['email'], $_POST['password']);
      if ($user) {
          session_regenerate_id(true);
          
          $this->logIn($user);
         
          User::rememberLogin($user->id,  $user->email);
          
          $this->redirect('/home');
      } else {
        View::renderTemplate('Home/index.html',[
            'email' => $_POST['email'],
            'login_error' => 'Password wrong!'
        ]);
      }
  }

  public function logoutAction() {
    $_SESSION = [];

    if (ini_get('session.use.cookies')) {
      $params = session_get_cookie_params();

      setcookie(
        session_name(),
        '',
        time() - 4200,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
      );
    }

    session_destroy();

    User::forgetLogin();

    $this->redirect('/');
  }
}
