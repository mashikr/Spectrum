<?php

namespace App\Controllers;

use \Core\View;
use \App\Config;
use \App\Flash;
use \App\Models\User;
use \App\Models\Post;


class Profile extends \Core\Controller {

  public function ownAction() {
      $this->before();

      if (User::getFriendList()) {
        $count = count(explode(',', User::getFriendList()));
      } else {
        $count = 0;
      }

      $posts = Post::getOwnPost();
      
      if ($posts) {
        $allPost = [];
        foreach ($posts as $post) {
          $wordLimit = 20;

          if (str_word_count($post['content']) > 20) {
            $post['content'] = $this->limitContent($post['content'], $wordLimit);
          }

          $post['time'] = $this->calcTime($post['date-time']);
          $post['date_time'] = $this->calcTimeFormate($post['date-time']);
          $post['firstname'] = $_SESSION['user']['firstname'];
          $post['lastname'] = $_SESSION['user']['lastname'];
          $post['profile_pic'] = $_SESSION['user']['profile_pic'];
          
          array_push($allPost, $post);
        }
      }
//print_r($allPost);
      View::renderTemplate('Profile/view.html', [
        'countFriend' => $count,
        'posts' => $allPost
      ]);
  }

  public function anotherAction() {
    $this->before();
    echo $this->route_params['id'];
    View::renderTemplate('Profile/view.html');
  }
}