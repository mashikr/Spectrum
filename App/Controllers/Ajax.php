<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\Post;
use \App\Models\UpdateProfile;
use \App\Models\Findfriend;
use \App\Models\Notification;
use \App\Models\Message;

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

            if ($_POST['type'] == 'profile_pic') {
                UpdateProfile::setProfilePhotoTophoto();
            } else {
                UpdateProfile::setCoverPhotoTophoto();
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

    public function sendRequestAction() {
        $this->before();

        if ($_POST['id']) {
           if (Findfriend::sendRequest($_POST['id'])) {
            $user = User::getUserById($_POST['id']);
            $user['status'] = 1;
            echo json_encode($user);
           } else {
               echo false;
           }
        }
    }

    public function cancelRequestAction() {
        $this->before();

        if ($_POST['id']) {
           if (Findfriend::cancelRequest($_POST['id'])) {
            $user = User::getUserById($_POST['id']);
            $user['status'] = 1;
            echo json_encode($user);
           } else {
               echo false;
           }
        }
    }

    public function acceptRequestAction() {
        $this->before();

        if ($_POST['id']) {
            $id = $_POST['id'];

            /// get current user friend list
            $cur_user_friends =  User::getFriendList();

            if ($cur_user_friends) {
                $cur_user_friends .= "," . $id;
            } else {
                $cur_user_friends = $id;
            }

            Findfriend::addFriend($_SESSION['user_id'], $cur_user_friends);

            //// get req sender friend list
            $friends =  User::getFriendList($id);
            
            if ($friends) {
                $friends .= "," . $_SESSION['user_id'];
            } else {
                $friends = $_SESSION['user_id'];
            }

            Findfriend::addFriend($id, $friends);
            Notification::acceptReqNotify($id);

            if (Findfriend::declineRequest($id)) {
                echo true;
            } else {
                echo false;
            }

         }
    }

    public function declineRequestAction() {
        $this->before();

        if ($_POST['id']) {
            $id = $_POST['id'];
            if (Findfriend::declineRequest($id)) {
                echo true;
            } else {
                echo false;
            }
        }
    }

    public function seenAllReqAction() {
        $this->before();

        if ($_POST['data']) {
            if (Findfriend::seenAllReq()) {
                echo true;
            } else {
                echo false;
            }
        }
    }

    public function likePostAction() {
        $this->before();

        if ($_POST['id']) {
            $id = $_POST['id'];
            $like_id = Post::likePost($id);
            $post_author = Post::getPostAuthor($id);

            if ($post_author == $_SESSION['user_id']) {
                echo true;
            } else {
               if (Notification::likesNotify($id, $like_id)) {
                   echo true;
               } else {
                   echo false;
               }
            }
        }
    }

    public function unlikePostAction() {
        $this->before();

        if ($_POST['id']) {
            $id = $_POST['id'];
            if (Post::unlikePost($id)) {
                echo true;
            } else {
                echo false;
            }
        }
    }

    public function commentTextAction() {
        $this->before();

        if ($_POST['id']) {
            $id = $_POST['id'];
            $comment = $_POST['comment'];
            $comment_id = Post::commentText($id, $comment);
            $post_author = Post::getPostAuthor($id);
            if ($post_author == $_SESSION['user_id']) {
                echo true;
            } else {
                if (Notification::commentNotify($id, $comment_id)) {
                    echo true;
                } else {
                    echo false;
                }
            }
        }
    }

    public function commentEmojiAction() {
        $this->before();

        if ($_POST['id']) {
            $id = $_POST['id'];
            $emoji = $_POST['emoji'];
            $comment_id = Post::commentEmoji($id, $emoji);
            $post_author = Post::getPostAuthor($id);

            if ($post_author == $_SESSION['user_id']) {
                echo true;
            } else {
                if (Notification::commentNotify($id, $comment_id)) {
                    echo true;
                } else {
                    echo false;
                }
            }
        }
    }

    public function commentPhotoAction() {
        $this->before();

        if ($_POST) {
            $post_id = $_POST['post_id'];
            $file_name = $_FILES['comment-image']['name'];
            $file = $_FILES['comment-image']['tmp_name'];
            $type = $this->fileType($file_name, $_FILES['comment-image']['type']);

            if ($type != "photo") {
                echo "invalid";
                return;
            }

            if (!move_uploaded_file($file, "../public/commentImg/$file_name")) {
               echo "failed";
               return;
            }

            $comment_id = Post::commentPhoto($post_id, $file_name);
            $post_author = Post::getPostAuthor($post_id);
            
            if ($post_author == $_SESSION['user_id']) {
                echo true;
            } else {
                if (Notification::commentNotify($post_id, $comment_id)) {
                    echo true;
                } else {
                    echo false;
                }
            }
        }
    }

    public function getCommentsAction() {
        $this->before();

        if ($_POST['id']) {
            $this->renderComments($_POST['id']);
        }
    }

    protected function renderComments($post_id) {
        $allcomments = [];
        $comments = Post::getPostComments($post_id);
        if ($comments) {
            foreach ($comments as $comment) {
                $comment['time'] = $this->calcTime($comment['date-time']);
                array_push($allcomments, $comment);
            }
        }
        $author = Post::getPostAuthor($post_id);
        $post['id'] = $post_id;

        View::renderTemplate('Post/comments.html', [
            'comments' => $allcomments,
            'author' => $author,
            'post' => $post
        ]);
    }

    public function deleteCommentAction() {
        $this->before();

        if ($_POST['id']) {
            $comment = Post::getComment($_POST['id']);
            $post_id = $comment['post_id'];

            if ($comment['type'] == 'photo') {
                unlink('../public/commentImg/' . $comment['source']);
            }
            
            Post::decrementComment($post_id);

            if (Post::deleteComment($_POST['id'])) {
                $this->renderComments($post_id);
            } else {
                echo false;
            }
        }
    }

    public function getLikesAction() {
        $this->before();

        if ($_POST['id']) {
            $likes =  Post::getLikes($_POST['id']);

            View::renderTemplate('Post/likes.html', [
                'likes' => $likes
            ]);
        }
    }

    public function seenAllNotifyAction() {
        $this->before();

        if ($_POST['data']) {
            if (Notification::seenAllNotify()) {
                echo true;
            } else {
                echo false;
            }
        }
    }

    public function getMessageAction() {
        $this->before();

        if ($_POST['id']) {
            
            $messages = Message::getMessage($_POST['id']);
            $allmessages = [];
            if ($messages) {
                foreach ($messages as $message) {
                    $message['time'] = $this->calcTime($message['time']);
                    array_push($allmessages, $message);
                }
            }
            
            Message::seenMsg($_POST['id']);

            /// get messages
            $messageHolders = $this->getMessages();
            $unseenMsg = $messageHolders['unseen'];
            $chatHolders = $messageHolders['chatHolders'];

            echo json_encode([
                'status' => true,
                'unseen' => $unseenMsg,
                'chatHolder' => View::getTemplate('Navbar/chatHolder.html', [
                        'chatHolders' =>  $chatHolders
                    ]),
                'messages' => View::getTemplate('Messages/messages.html', [
                    'messages' => $allmessages
                    ])
                ]);
        }
    }
    
    public function sendMessageAction() {
        $this->before();

        if ($_POST) {
            if (Message::sendMessage($_POST['id'], $_POST['message'], $_POST['type'])) {
                if (isset($_POST['page']) && $_POST['page'] == 'msg') {
                    echo $this->messageHelper($_POST['id']);
                } else {
                    echo $this->sendMessageHelper($_POST['id']);
                }
            } else {
                echo json_encode(['status' => false]);
            }
        }
    }

    protected function sendMessageHelper($id) {
        $messages = Message::getMessage($id);
        $allmessages = [];
        if ($messages) {
            foreach ($messages as $message) {
                $message['time'] = $this->calcTime($message['time']);
                array_push($allmessages, $message);
            }
        }

        /// get messages
        $messageHolders = $this->getMessages();
        $chatHolders = $messageHolders['chatHolders'];

        return json_encode([
            'status' => true,
            'chatHolder' => View::getTemplate('Navbar/chatHolder.html', [
                    'chatHolders' =>  $chatHolders
                ]),
            'messages' => View::getTemplate('Messages/messages.html', [
                'messages' => $allmessages
                ])
            ]);
    }

    protected function messageHelper($id) {
        $messages = Message::getMessage($id);
        $allmessages = [];
        if ($messages) {
            foreach ($messages as $message) {
                $message['time'] = $this->calcTime($message['time']);
                array_push($allmessages, $message);
            }
        }

        /// get messages
        $messageHolders = $this->getMessages();
        $chatHolders = $messageHolders['chatHolders'];

        return json_encode([
            'status' => true,
            'chatHolder' => View::getTemplate('Messages/chatHolder.html', [
                    'chatHolders' =>  $chatHolders
                ]),
            'messages' => View::getTemplate('Messages/messages.html', [
                'messages' => $allmessages
                ])
            ]);
    }

    public function sendImageAction() {
        $this->before();
        
        if ($_POST) {
            $user_id = $_POST['user_id'];
            $file_name = $_FILES['send-image']['name'];
            $file = $_FILES['send-image']['tmp_name'];
            $type = $this->fileType($file_name, $_FILES['send-image']['type']);

            if ($type != "photo") {
                echo json_encode(['status' => false, 'msg' => 'invalid']);
                return;
            }

            if (!move_uploaded_file($file, "../public/messageImg/$file_name")) {
                echo json_encode(['status' => false, 'msg' => 'failed']);
                return;
            }

            if (Message::sendMessage($user_id, $file_name, 'image')) {
                if (isset($_POST['page']) && $_POST['page'] == 'msg') {
                    echo $this->messageHelper($user_id);
                } else {
                    echo $this->sendMessageHelper($user_id);
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'failed']);
            }
        }
    }

    public function sendFileAction() {
        $this->before();
        
        if ($_POST) {
            $user_id = $_POST['user_id'];
            $file_name = $_FILES['send-file']['name'];
            $file = $_FILES['send-file']['tmp_name'];
            $type = $this->fileType($file_name, $_FILES['send-file']['type']);

            if ($type == "invalid" || $type == "photo") {
                echo json_encode(['status' => false, 'msg' => 'invalid']);
                return;
            }

            if (!move_uploaded_file($file, "../public/message$type/$file_name")) {
                echo json_encode(['status' => false, 'msg' => 'failed']);
                return;
            }
            if (Message::sendMessage($user_id, $file_name, $type)) {
                if (isset($_POST['page']) && $_POST['page'] == 'msg') {
                    echo $this->messageHelper($user_id);
                } else {
                    echo $this->sendMessageHelper($user_id);
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'failed']);
            }
        }
    }
}

?>