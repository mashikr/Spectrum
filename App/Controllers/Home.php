<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Post;
use \App\Models\User;

class Home extends \Core\Controller {

    public function indexAction() {
        if ($this->userId()) {
            $this->redirect('/home');
        }
        View::renderTemplate('Home/index.html');
    }

    public function userAction() {
        $this->before();

        $posts = Post::newsfeedPost(User::getFriendList());

        if ($posts) {
            $allPost = [];
            foreach ($posts as $post) {
              $wordLimit = 20;
    
              if (str_word_count($post['content']) > $wordLimit) {
                $post['content'] = $this->limitContent($post['content'], $wordLimit);
              }
    
              $post['time'] = $this->calcTime($post['date-time']);
              $post['date_time'] = $this->calcTimeFormate($post['date-time']);
              $post['option'] = 'newsfeed';
              
              array_push($allPost, $post);
            }
          }

        View::renderTemplate('Home/user.html', [
            'page' => 'user',
            'posts' => $allPost
        ]);
    }
}

?>