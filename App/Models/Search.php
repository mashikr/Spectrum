<?php

namespace App\Models;

use PDO;

class Search extends \Core\Model {

    public static function getSearchItem($key) {
        $cur_user = $_SESSION['user_id'];
        $sql = "SELECT `id`,`firstname`,`lastname`,`profile_pic` FROM `users` WHERE (CONCAT(firstname,' ',lastname) LIKE '%$key%' OR `email` LIKE '%$key%') AND id != $cur_user";
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}