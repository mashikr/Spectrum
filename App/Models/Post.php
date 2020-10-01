<?php

namespace App\Models;

use PDO;
use \App\Config;

class Post extends \Core\Model {

    public static function addPost($content = null, $type, $file_name = null, $privacy) {
        $sql = "INSERT INTO `posts`(`author_id`, `content`, `type`, `file_name`, `privacy`) VALUES (:author, :content, :type, :file_name, :privacy)";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':author', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
        $stmt->bindValue(':file_name', $file_name, PDO::PARAM_STR);
        $stmt->bindValue(':privacy', $privacy, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function getOwnPost() {
        $sql = "SELECT * FROM `posts` WHERE `author_id` = :author ORDER BY id DESC";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':author', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function newsfeedPost($friends) {

        $sql = "SELECT posts.*,users.firstname,users.lastname,users.profile_pic FROM `posts` LEFT JOIN users ON users.id = posts.author_id WHERE (`author_id` = " . $_SESSION['user_id'] . ") OR (`privacy` = 'public')";
        if ($friends) {
            $sql .= "OR (`author_id` IN (" . $friends . ") AND `privacy` = 'friends')";
        }
        $sql .= " ORDER BY id DESC LIMIT 0," . Config::POST_LIMIT;

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function delete($id) {
        $sql = "SELECT `type`,`file_name` FROM `posts` WHERE `id` = :id";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post['file_name']) {
            if ($post['type'] == 'photo' || $post['type'] == 'profile_pic' || $post['type'] == 'cover_pic') {

                $sql = "SELECT `gender`, `profile_pic`,`cover_pic` FROM `users` WHERE `id` = :id";
                $stmt = $db->prepare($sql);
        
                $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($post['file_name'] == $user['profile_pic']) {
                    if ($user['gender'] == 'Male') {
                        $photo = 'male-user.png';
                    } else {
                        $photo = 'female-user.png';
                    }
                    $sql = "UPDATE `users` SET `profile_pic` = :photo WHERE `id` = :id";
                    $stmt = $db->prepare($sql);
            
                    $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
                    $stmt->bindValue(':photo', $photo, PDO::PARAM_STR);
                    $stmt->execute();
                    $_SESSION['user']['profile_pic'] = $photo;
                } else if ($post['file_name'] == $user['cover_pic']) {
                    $sql = "UPDATE `users` SET `cover_pic` = 'dark-bg.jpg' WHERE `id` = :id";
                    $stmt = $db->prepare($sql);
            
                    $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
                    $stmt->execute();
                    $_SESSION['user']['cover_pic'] = 'dark-bg.jpg';
                }

                unlink('../public/img/' . $post['file_name']);
            } else {
                unlink('../public/' . $post['type'] . '/' . $post['file_name']);
            }
        }
        

        $sql = "DELETE FROM `posts` WHERE `id` = :id";

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}