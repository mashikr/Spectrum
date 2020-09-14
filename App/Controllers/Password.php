<?php

namespace App\Controllers;

use \Core\View;
use \App\Flash;
use \App\Models\User;


class Password extends \Core\Controller {

    public function forgotAction() {
        View::renderTemplate('Password/forgot.html');
    }

    public function sendResetReqAction() {
        $user_exists = User::findByEmail($_POST['email']);
        if ($user_exists) {
            if (User::passwordResetStart($_POST['email'])) {
                Flash::addMessage('Sending a email successfuly, Please check inbox', 'success');
                $this->redirect('/');
            } else {
                Flash::addMessage('Something went wrong!', 'error');
                View::renderTemplate('Password/forgot.html');
            }            
        } else {
            Flash::addMessage('This email is\'t exists', 'error');
            View::renderTemplate('Password/forgot.html');
        }
    }

    public function resetAction() {
        $token = $this->route_params['token'];

       if ($this->getUser($token)) {
            View::renderTemplate('Password/reset.html', [
                'token' => $token
            ]);
       }
    }

    public function newAction() {
        $token = $_POST['token'];
        $user = $this->getUser($token);

        if ($user->resetPassword($_POST['password'], $_POST['confirm_password'])) {
            $this->redirect('/password/success');
        } else {
            View::renderTemplate('Password/reset.html', [
                'token' => $token,
                'user' => $user
            ]);
        }
    }

    public function getUser($token) {
        $user = User::findByToken($token);

        if ($user) {
            return $user;
        } else {
            View::renderTemplate('Password/error.html');
        }
    }

    public function successAction() {
        View::renderTemplate('Password/success.html');
    }
}