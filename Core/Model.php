<?php

namespace Core;

use PDO;
use App\Config;

abstract class Model {

    protected static function getDB() {

        $db = new PDO("mysql:host=" . Config::DB_HOST .";dbname=" . Config::DB_NAME , Config::DB_USER, Config::DB_PASSWORD);

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $db;
        
    }
}

?>