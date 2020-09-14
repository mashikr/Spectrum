<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\Mail;

class User extends \Core\Model {
    
    public $errors = [];

    public function __construct($data = []) {
        foreach($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function create() {

       $this->validate();

        if (empty($this->errors)) {
            try {
                $token = new Token();
                $hashed_token = $token->getHash();
                $active_token = $token->getValue();

                $this->password = password_hash($this->password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO `users`(`username`, `email`, `password`, `account_active_hash`) VALUES (:username,:email,:password,:active_hash)";
                
                $db = static::getDB();
                $stmt = $db->prepare($sql);
                                                      
                $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
                $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
                $stmt->bindValue(':password', $this->password, PDO::PARAM_STR);
                $stmt->bindValue(':active_hash', $hashed_token, PDO::PARAM_STR);
                
                if ($this->sendActivateEmail($active_token, $this->email)) {
                    return $stmt->execute();
                }
                
                return false;
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        return false;
    }

    protected function sendActivateEmail($token, $email) {
        $url = "http://localhost/login-mvc/signup/active/" . $token;

        $text = "Please click on the following URL to active your account: " . $url;
        $html = "Please click <a href='$url'>here</a> to active your account";

        return Mail::send($email, 'Activate account', $text, $html);
    }

    protected function validate() {
        if ($this->username == '') {
            $this->errors[] = 'Name is required';
        }

        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Invalid email';
        }

        if ($this->emailExists($this->email, $this->id ?? null)) {
            $this->errors[] = 'This email is already taken';
        }

        if ($this->password != $this->confirm_password) {
            $this->errors[] = 'Password do not match';
        }

        if (strlen($this->password) < 6) {
            $this->errors[] = 'Please enter at least 6 character for the password';
        }

        if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
            $this->errors[] = 'Password needs at least one letter';
        }

        if (preg_match('/.*\d+.*/i', $this->password) == 0) {
            $this->errors[] = 'Password needs at least one number';
        }
    }

    public static function activeAccount($token) {
        $token = new Token($token);
        $hashed_token = $token->getHash();

        $sql = "UPDATE `users` SET `account_active_hash`= NULL,`active`= 1 WHERE `account_active_hash` = :hashed_token";
                
        $db = static::getDB();
        $stmt = $db->prepare($sql);
                                                
        $stmt->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public static function emailExists($email, $ignore_id = null) {
        $user = static::findByEmail($email);

        if ($user) {
            if ($user->id != $ignore_id) {
                return true;
            }
        }

        return false;
    }

    public static function findByEmail($email) {
        $sql = 'SELECT * FROM users WHERE email = :email AND active = 1';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    public static function authenticate($email, $password) {
        $user = static::findByEmail($email);

        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }

        return false;
    }

    public static function rememberLogin($id, $name, $email) {
        $token = new Token();
        $hashed_token = $token->getHash();
        $remember_token = $token->getValue();

        $expire_timestamp = time() + 60 * 60 * 24 * 30;

        setcookie('remember_me', $remember_token, $expire_timestamp, '/');

        $sql = 'INSERT INTO `remember_logins`(`token`, `user_id`, `name`, `email`, `expired_at`) VALUES (:token, :user_id, :name, :email, :expire_at)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $id, PDO::PARAM_STR);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':expire_at', date('Y-m-d H:i:s', $expire_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function getUserByToken($token_code) {
        $token = new Token($token_code);
        $token_hash = $token->getHash();

        $sql = 'SELECT user_id, name, email FROM `remember_logins` WHERE `token` = :token';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':token', $token_hash, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function forgetLogin() {
        $cookie = $_COOKIE['remember_me'] ?? false;

        if ($cookie) {
            $token = new Token($cookie);
            $token_hash = $token->getHash();

            $sql = 'DELETE FROM `remember_logins` WHERE `token` = :token';
            $db = static::getDB();

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':token', $token_hash, PDO::PARAM_STR);

            $stmt->execute();

            setcookie('remember_me', '', time() - 3600, '/');      
        }
    }

    public static function passwordResetStart($email) {
        $token = new Token();
        $hashed_token = $token->getHash();
        $token = $token->getValue();
        $expiry_time = time() + 60 * 60 * 2;

        $sql = "UPDATE `users` SET `password_reset_hash`= :hashed_token,`password_reset_expire`= :expiry_time WHERE `email` = :email";

        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':expiry_time', \date('Y-m-d H:i:s', $expiry_time), PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        if (static::sendEmail($email, $token)) {
            return $stmt->execute();
        }
        return false;
    }
    public static function sendEmail($email, $token) {
        $url = "http://localhost/login-mvc/password/reset/" . $token;

        $text = "Please click on the following URL to reset your password: " . $url;
        $html = "Please click <a href='$url'>here</a> to reset your password";

        return Mail::send($email, 'Password reset', $text, $html);
    }

    public static function findByToken($token) {
        $token = new Token($token);
        $hashed_token = $token->getHash();

        $sql = 'SELECT * FROM users WHERE 	password_reset_hash = :hashed_token';

        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $user = $stmt->fetch();

        if ($user) {
            if (strtotime($user->password_reset_expire) > time()) {
                return $user;
            }
        }
    }

    public function resetPassword($password, $confirm_password) {
        $this->password = $password;
        $this->confirm_password = $confirm_password;

        $this->validate();

        if (empty($this->errors)) {
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = "UPDATE `users` SET `password`= :password_hash,`password_reset_hash`= NULL,`password_reset_expire`= NULL WHERE `id` = :id";

            $db = static::getDB();

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

            return $stmt->execute();
        }

        return false;
    }
}

?>