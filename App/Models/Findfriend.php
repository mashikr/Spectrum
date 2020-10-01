<?php

namespace App\Models;

use PDO;

class Findfriend extends \Core\Model {

    public static function findFriend($current_friends) {

        if ($current_friends) {
            $current_friends = $_SESSION['user_id'] . "," . $current_friends;
        } else {
            $current_friends = $_SESSION['user_id'];
        }

        $sql = "SELECT `id`,`firstname`,`lastname`,`friends`,`profile_pic` FROM `users` WHERE id NOT IN (:current_friends)";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':current_friends', $current_friends, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}