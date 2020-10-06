<?php

namespace App\Controllers;

use \Core\View;
use App\Flash;
use App\Config;
use \App\Models\Post;

class Posts extends \Core\Controller {

    public function addAction() {
        $this->before();

        if ($_SERVER['CONTENT_LENGTH'] > 41943040 ) {
            Flash::addMessage("File size limit exceed", 'failed');
            if (isset($_POST['timeline'])) {
                $this->redirect('/profile');
            }
            $this->redirect('/home');
        }

       if(isset($_POST['submit'])) {
           $content =  $_POST['post'];
           $privacy = $_POST['privacy'];
           $file_name = $_FILES['post-file']['name'];
           $file = $_FILES['post-file']['tmp_name'];

           if ($content || $file_name) {

                if ($file_name) {

                    $type = $this->fileType($file_name, $_FILES['post-file']['type']);

                    if ($type == 'invalid') {
                        Flash::addMessage("Invalid file", 'failed');
                        if (isset($_POST['timeline'])) {
                            $this->redirect('/profile');
                        }
                        $this->redirect('/home');
                    }

                    $dir = $type == 'photo' ? 'img' : $type;

                    if (!move_uploaded_file($file, "../public/$dir/$file_name")) {
                        Flash::addMessage("File upload failed", 'failed');
                        if (isset($_POST['timeline'])) {
                            $this->redirect('/profile');
                        }
                        $this->redirect('/home');
                    }

                } else {
                    $type = 'post';
                }

                if (Post::addPost(htmlspecialchars($content), $type, $file_name, $privacy)) {
                    Flash::addMessage("Successfully Posted", 'success');
                    if (isset($_POST['timeline'])) {
                        $this->redirect('/profile');
                    }
                    $this->redirect('/home');
                } else {
                    Flash::addMessage("Posting fail", 'failed');
                    if (isset($_POST['timeline'])) {
                        $this->redirect('/profile');
                    }
                    $this->redirect('/home');
                }
           } else {
               if (isset($_POST['timeline'])) {
                    $this->redirect('/profile');
               }
               $this->redirect('/home');
           }
           
       }
    }

    public function showAction() {
        $this->before();

        $post_id = $this->route_params['id'];

        /// get post by id
        $post = Post::getPostById($post_id);

        $post['time'] = $this->calcTime($post['date-time']);
        $post['date_time'] = $this->calcTimeFormate($post['date-time']);
        $post['like'] = Post::isLiked($post['id']);
        $post_author = $post['author_id'];

        $allPost[0] = $post;
         /// get friends requests ////
         $allRequests = $this->getRequest();
         $countRequest = 0;
 
         if ($allRequests) {
           foreach ($allRequests as $request) {
             if ($request['seen'] == 0) {
               $countRequest++;
             }
           }
         }
        
         /// get comments
        $allcomments = [];
        $comments = Post::getPostComments($post_id);
        if ($comments) {
            foreach ($comments as $comment) {
                $comment['time'] = $this->calcTime($comment['date-time']);
                array_push($allcomments, $comment);
            }
        }

        //// get notification /////
        $notifications =  $this->getNotifications();
        $allNotify = $notifications['allNotify'];

        View::renderTemplate('Post/view.html', [
            'page' => 'post',
            'friendsRrequests' =>  $allRequests,
            'countRequest' => $countRequest,
            'posts' => $allPost,
            'comments' => $allcomments,
            'author' => $post_author,
            'notifications' => $allNotify
        ]);
    }

    public function deleteAction() { 
        $this->before();

        if (Post::delete($this->route_params['id'])) {
            Flash::addMessage("Successfully delete!", 'success');
        } else {
            Flash::addMessage("Deleting fail", 'failed');
        }
        $this->redirect('/profile');
    }
}

?>