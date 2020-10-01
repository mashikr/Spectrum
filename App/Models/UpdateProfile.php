<?php
namespace App\Models;

use PDO;

class UpdateProfile extends \Core\Model {

    public static function worksAt($data) {
        $sql = "UPDATE `users` SET `works_at`= :worksAt WHERE `id` = :id";
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':worksAt', $data, PDO::PARAM_STR);
        $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function studied($data) {
        $sql = "UPDATE `users` SET `studied`= :studied WHERE `id` = :id";
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':studied', $data, PDO::PARAM_STR);
        $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function liveIn($data) {
        $sql = "UPDATE `users` SET `live_in`= :live_in WHERE `id` = :id";
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':live_in', $data, PDO::PARAM_STR);
        $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function homeTown($data) {
        $sql = "UPDATE `users` SET `home_town`= :home_town WHERE `id` = :id";
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':home_town', $data, PDO::PARAM_STR);
        $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function phoneNo($data) {
        $sql = "UPDATE `users` SET `phone`= :phone WHERE `id` = :id";
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':phone', $data, PDO::PARAM_STR);
        $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function photo($type, $file_name) {
        $sql = "UPDATE `users` SET $type = :file_name WHERE `id` = :id";
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':file_name', $file_name, PDO::PARAM_STR);
        $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);

        return $stmt->execute();
    }
}
