<?php

namespace App\Models;

use PDO;
use \App\Config;

class Notification extends \Core\Model {

    public static function acceptReqNotify($receiver) {
        $sender = $_SESSION['user_id'];
        $sql = "INSERT INTO `friendreqnotify`(`sender`, `receiver`) VALUES ($sender, $receiver)";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        return $stmt->execute();
    }

    public static function likesNotify($post_id, $like_id) {
        $sender = $_SESSION['user_id'];
        $sql = "INSERT INTO `likesnotify`(`sender_id`, `post_id`, `like_id`) VALUES ($sender, $post_id, $like_id)";
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        return $stmt->execute();
    }

    public static function commentNotify($post_id, $comment_id) {
        $sender = $_SESSION['user_id'];
        $sql = "INSERT INTO `commentnotify`(`sender_id`, `post_id`, `comment_id`) VALUES ($sender, $post_id, $comment_id)";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        return $stmt->execute();
    }

    public static function getacceptReqNotify() {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT friendreqnotify.*,users.firstname,users.lastname,users.profile_pic FROM `friendreqnotify` LEFT JOIN users ON users.id = friendreqnotify.sender WHERE `receiver` = $user_id";
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getlikesNotify() {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT likesnotify.*,users.firstname,users.lastname,users.profile_pic,posts.type,posts.author_id FROM `likesnotify` LEFT JOIN users ON users.id = likesnotify.sender_id LEFT JOIN posts ON posts.id = likesnotify.post_id WHERE posts.author_id = $user_id";
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getcommentNotify() {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT commentnotify.*,users.firstname,users.lastname,users.profile_pic,posts.type,posts.author_id FROM `commentnotify` LEFT JOIN users ON users.id = commentnotify.sender_id LEFT JOIN posts ON posts.id = commentnotify.post_id WHERE posts.author_id = $user_id";
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateFriendReqNotify($user_id) {
        $cur_user = $_SESSION['user_id'];
        $sql = "DELETE FROM `friendreqnotify` WHERE (`sender` = $user_id AND `receiver` = $cur_user) OR (`sender` = $cur_user AND `receiver` = $user_id)";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
    }

    public static function seenAllNotify() {
        $user_id = $_SESSION['user_id'];
        $sql = "UPDATE `likesnotify` LEFT JOIN posts  ON posts.id = likesnotify.post_id SET `seen`= 1 WHERE posts.author_id = $user_id";
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $sql = "UPDATE `commentnotify` LEFT JOIN posts  ON posts.id = commentnotify.post_id SET `seen`= 1 WHERE posts.author_id = $user_id";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $sql = "UPDATE `friendreqnotify` SET `seen` = 1 WHERE `receiver` = $user_id";
        $stmt = $db->prepare($sql);
        return $stmt->execute();
    }
}