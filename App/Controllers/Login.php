<?php

namespace App\Controllers;

use \Core\View;
use \App\Flash;
use \App\Models\User;


class Login extends \Core\Controller {

  public function loginAction() {
      View::renderTemplate('Login/login.html',[
        'page' => 'login'
    ]);
  }

  public function newAction() {
      $user = User::authenticate($_POST['email'], $_POST['password']);
      
      if ($user) {
          session_regenerate_id(true);
          $_SESSION['user_id'] = $user->id;
          $_SESSION['firstname'] = $user->firstname;
          $_SESSION['lastname'] = $user->lastname;
          $_SESSION['email'] = $user->email;

         
          User::rememberLogin($user->id,  $user->email);
          
          $this->redirect('/profile');
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
