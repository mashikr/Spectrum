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

        $sql = "SELECT `id`,`firstname`,`lastname`,`friends`,`profile_pic` FROM `users` WHERE id NOT IN ($current_friends)";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function sendRequest($id) {
        $sql = "INSERT INTO `friend_request`(`sender`, `receiver`) VALUES (:sender, :receiver)";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':sender', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':receiver', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function cancelRequest($id) {
        $sql = "DELETE FROM `friend_request` WHERE `sender` = :sender AND `receiver` = :receiver";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':sender', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':receiver', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function getRequest() {
        $sql = "SELECT friend_request.*,users.firstname,users.lastname,users.profile_pic FROM `friend_request` LEFT JOIN users ON users.id = friend_request.sender WHERE friend_request.`receiver` = :id ORDER BY id DESC";
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function seenAllReq() {
        $sql = "UPDATE `friend_request` SET `seen`= 1 WHERE `receiver` = :id";
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function getSendRequests() {

        $sql = "SELECT friend_request.*,users.firstname,users.lastname,users.profile_pic FROM `friend_request` LEFT JOIN users ON users.id = friend_request.receiver WHERE friend_request.`sender` = :id";
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function addFriend($id, $friends) {
        $sql = "UPDATE `users` SET `friends`= '$friends' WHERE `id` = $id";
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        return $stmt->execute();
    }

    public static function declineRequest($id) {
        $sql = "DELETE FROM `friend_request` WHERE `sender` = :sender AND `receiver` = :receiver";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':sender', $id, PDO::PARAM_INT);
        $stmt->bindValue(':receiver', $_SESSION['user_id'], PDO::PARAM_INT);

        return $stmt->execute();
    }
}