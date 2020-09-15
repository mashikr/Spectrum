<?php

namespace App\Controllers;

use \App\Models\User;

class Ajax extends \Core\Controller {

    public function emailValidateAction() {
        $is_valid = ! User::emailExists($_GET['email']);

        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }
}

?>