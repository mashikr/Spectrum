<?php

namespace App\Models;

use PDO;

class Message extends \Core\Model {
    public static function getSender() {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT DISTINCT MAX(id),`sender` FROM `messages` WHERE `receiver` = $user_id  GROUP BY `sender`";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getReceiver() {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT DISTINCT MAX(id),`receiver` FROM `messages` WHERE `sender` = $user_id GROUP BY `receiver`";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUnseenMsg($id) {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT COUNT(id) FROM messages WHERE (`sender` = $id AND `receiver` =  $user_id AND seen = 0)";
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_NUM);
    }

    public static function getTimeById($id) {
        $sql = "SELECT `time` FROM `messages` WHERE `id` = $id";
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $time =  $stmt->fetch(PDO::FETCH_NUM);
        return $time[0];
    }

    public static function getMessage($id) {
        $cur_user = $_SESSION['user_id'];
        $sql = "SELECT * FROM `messages` WHERE (`sender` = $cur_user AND `receiver` = $id) OR (`receiver` = $cur_user AND `sender` = $id)";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function seenMsg($sender_id) {
        $cur_user = $_SESSION['user_id'];
        $sql = "UPDATE `messages` SET `seen`= 1 WHERE `sender` = $sender_id AND `receiver` = $cur_user";
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
    }

    public static function sendMessage($id, $message, $type) {
        $cur_user = $_SESSION['user_id'];
        $sql = "INSERT INTO `messages`(`sender`, `receiver`, `message`, `type`) VALUES ($cur_user, $id, '$message', '$type')";
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        return $stmt->execute();
    }
}