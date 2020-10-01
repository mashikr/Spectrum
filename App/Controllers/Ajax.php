<?php

namespace App\Controllers;

use \App\Models\User;
use \App\Models\Post;
use \App\Models\UpdateProfile;

class Ajax extends \Core\Controller {

    public function emailValidateAction() {
        $is_valid = ! User::emailExists($_GET['email']);

        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }

    public function updateAboutAction() {
        $this->before();

        if ($_POST['type'] == 'works-At') {
            if (UpdateProfile::worksAt($_POST['data'])) {
                $_SESSION['user']['works_at'] = $_POST['data'];
                echo true;
            } else {
                echo 'failed';
            }
        } elseif ($_POST['type'] == 'studied') {
            if (UpdateProfile::studied($_POST['data'])) {
                $_SESSION['user']['studied'] = $_POST['data'];
                echo true;
            } else {
                echo 'failed';
            }
        } elseif ($_POST['type'] == 'lives-In') {
            if (UpdateProfile::liveIn($_POST['data'])) {
                $_SESSION['user']['live_in'] = $_POST['data'];
                echo true;
            } else {
                echo 'failed';
            }
        } elseif ($_POST['type'] == 'home-Town') {
            if (UpdateProfile::homeTown($_POST['data'])) {
                $_SESSION['user']['home_town'] = $_POST['data'];
                echo true;
            } else {
                echo 'failed';
            }
        } elseif ($_POST['type'] == 'phone-No') {
            if (UpdateProfile::phoneNo($_POST['data'])) {
                $_SESSION['user']['phone'] = $_POST['data'];
                echo true;
            } else {
                echo 'failed';
            }
        }
        
    }

    public function updatePhotoAction() {
        $this->before();

        if ($_SERVER['CONTENT_LENGTH'] > 41943040 ) {
            echo "limitExceed";
            return;
        } else {
            $file_name = $_FILES['image']['name'];
            $file = $_FILES['image']['tmp_name'];
            $type = $this->fileType($file_name, $_FILES['image']['type']);

            if ($type != "photo") {
                echo "invalid";
                return;
            }

            if (!move_uploaded_file($file, "../public/img/$file_name")) {
               echo "failed";
               return;
            }

            if (Post::addPost('', $_POST['type'], $file_name, 'Public') && UpdateProfile::photo($_POST['type'], $file_name)) {
                $_SESSION['user'][$_POST['type']] = $file_name;
                echo true;
                return;
            } else {
                echo false;
            }

        }
    }
}

?>