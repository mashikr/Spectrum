<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Post;
use \App\Models\User;
use \App\Models\Findfriend;
use \App\Models\Notification;

class Home extends \Core\Controller {

    public function indexAction() {
        if ($this->userId()) {
            $this->redirect('/home');
        }
        View::renderTemplate('Home/index.html');
    }

    public function userAction() {
        $this->before();

        /// get posts ///
        $posts = Post::newsfeedPost(User::getFriendList());

        $allPost = [];
        if ($posts) {
            foreach ($posts as $post) {
              $wordLimit = 20;
    
              if (str_word_count($post['content']) > $wordLimit) {
                $post['content'] = $this->limitContent($post['content'], $wordLimit);
              }
    
              $post['time'] = $this->calcTime($post['date-time']);
              $post['date_time'] = $this->calcTimeFormate($post['date-time']);
              $post['option'] = 'newsfeed';
              $post['like'] = Post::isLiked($post['id']);
              
              array_push($allPost, $post);
            }
          }

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

        //// get notification /////
        $notifications =  $this->getNotifications();
        $allNotify = $notifications['allNotify'];
        $countNotify = $notifications['count'];

        View::renderTemplate('Home/user.html', [
            'page' => 'user',
            'friendsRrequests' =>  $allRequests,
            'countRequest' => $countRequest,
            'posts' => $allPost,
            'notifications' => $allNotify,
            'countNotify' => $countNotify
        ]);
    }
}

?>