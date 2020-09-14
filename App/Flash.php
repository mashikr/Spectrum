<?php

namespace App;

class Flash {
    public static function addMessage($msg, $type) {
        if (! isset($_SESSION['flash_notify'])) {
            $_SESSION['flash_notify'] = [];
        }

        $_SESSION['flash_notify'] [] = ['msg' => $msg, 'type' => $type];
    }

    public static function getMessage() {
        $msg = '';
        if (isset($_SESSION['flash_notify'])) {
            $msg = $_SESSION['flash_notify'];
            unset($_SESSION['flash_notify']);
        }

        return $msg;
    }
}