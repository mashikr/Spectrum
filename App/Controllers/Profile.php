<?php

namespace App\Controllers;

use \Core\View;
use \App\Config;
use \App\Flash;
use \App\Models\User;
use \App\Models\Post;
use \App\Models\UpdateProfile;


class Profile extends \Core\Controller {

  public function ownAction() {
      $this->before();

      $allfriends = [];
      if (User::getFriendList()) {
        $friends_str = User::getFriendList();
        $friends = User::getFriends($friends_str);
        $count = count($friends);

        foreach ($friends as $friend) {
          if (!$friend['friends']) {
            $friend['mutual'] = 'No';
          } else {
            $friend['mutual'] = count(array_intersect(explode(",", $friends_str),explode(",", $friend['friends'])));
            if ($friend['mutual'] == 0) {
              $friend['mutual'] = 'No';
            }
          }

          array_push($allfriends,$friend);
        }
      } else {
        $count = 0;
      }

      $posts = Post::getOwnPost();
      
      $allPost = [];
      if ($posts) {
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
          $post['like'] = Post::isLiked($post['id']);
          
          array_push($allPost, $post);
        }
      }
      
      $photos = User::getPhotos($_SESSION['user_id']);

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

      /// get messages
      $messages = $this->getMessages();
      $unseenMsg = $messages['unseen'];
      $chatHolders = $messages['chatHolders'];

      //// get notification /////
      $notifications =  $this->getNotifications();
      $allNotify = $notifications['allNotify'];


      View::renderTemplate('Profile/view.html', [
        'countFriend' => $count,
        'friendsRrequests' =>  $allRequests,
        'countRequest' => $countRequest,
        'posts' => $allPost,
        'user' =>  $_SESSION['user'],
        'friends' => $allfriends,
        'photos' => $photos,
        'notifications' => $allNotify,
        'unseenMsg' => $unseenMsg,
        'chatHolders' => $chatHolders
      ]);
  }

  public function anotherAction() {
    $this->before();
    
    // get user data
    $id = $this->route_params['id'];
    $user = User::getUserById($id);
    $user['name'] = $user['firstname'] . " " . $user['lastname'];

    // get friens number
    $allfriends = [];
    if ($user['friends']) {
      $count = count(explode(',', $user['friends']));
      $friends = User::getFriends($user['friends']);

        foreach ($friends as $friend) {
          if (!$friend['friends']) {
            $friend['mutual'] = 'No';
          } else {
            $friend['mutual'] = count(array_intersect(explode(",", $user['friends']),explode(",", $friend['friends'])));
            if ($friend['mutual'] == 0) {
              $friend['mutual'] = 'No';
            }
          }

          array_push($allfriends,$friend);
        }
    } else {
      $count = 0;
    }

    //// get relation
    $relation = $this->getRelation($id);

    /// get posts
    $posts = Post::getOtherPost($id, $relation);

    // get photos
    $photos = User::getPhotos($id, $relation);
      
    $allPost = [];
      if ($posts) {
        foreach ($posts as $post) {
          $wordLimit = 20;

          if (str_word_count($post['content']) > 20) {
            $post['content'] = $this->limitContent($post['content'], $wordLimit);
          }

          $post['time'] = $this->calcTime($post['date-time']);
          $post['date_time'] = $this->calcTimeFormate($post['date-time']);
          $post['firstname'] = $user['firstname'];
          $post['lastname'] = $user['lastname'];
          $post['profile_pic'] = $user['profile_pic'];
          
          array_push($allPost, $post);
        }
      }

      //print_r($allfriends);

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
       /// get messages
       $messages = $this->getMessages();
       $unseenMsg = $messages['unseen'];
       $chatHolders = $messages['chatHolders'];

      //// get notification /////
      $notifications =  $this->getNotifications();
      $allNotify = $notifications['allNotify'];


      View::renderTemplate('Profile/view.html', [
        'user' => $user,
        'countFriend' => $count,
        'posts' => $allPost,
        'friends' => $allfriends,
        'friendsRrequests' =>  $allRequests,
        'countRequest' => $countRequest,
        'photos' => $photos,
        'relation' => $relation,
        'notifications' => $allNotify,
        'unseenMsg' => $unseenMsg,
        'chatHolders' => $chatHolders
      ]);
  }

  protected function getRelation($id) {
    $cur_user_Friends = User::getFriendList();

    if ($cur_user_Friends) {
      $cur_user_Friends = explode(",", $cur_user_Friends);

      if (in_array($id, $cur_user_Friends)) {
        return 'friend';
      }
    }

    if (User::isSendReq($id)) {
      return 'sendReq';
    }

    if (User::isReceiveReq($id)) {
      return 'receiveReq';
    }

    return 'suggest';
  }

  public function updateProfilePhotoAction() {
    $file_name = UpdateProfile::updateProfilePhoto($this->route_params['id']);
    $file_name = $file_name['file_name'];

    if (UpdateProfile::setProfilePhotoTophoto() && Post::addPost('', 'profile_pic', $file_name, 'Public')) {
        UpdateProfile::photo('profile_pic', $file_name);
        $_SESSION['user']['profile_pic'] = $file_name;

        $this->redirect('/profile');
    } else {
      Flash::addMessage("Profile photo update fail", 'failed');
    }
  }

  public function updateCoverPhotoAction() {
    $file_name = UpdateProfile::updateCoverPhoto($this->route_params['id']);
    $file_name = $file_name['file_name'];

      if (UpdateProfile::setCoverPhotoTophoto() && Post::addPost('', 'cover_pic', $file_name, 'Public')) {
          UpdateProfile::photo('cover_pic', $file_name);
          $_SESSION['user']['cover_pic'] = $file_name;

          $this->redirect('/profile');
      } else {
        Flash::addMessage("Cover photo update fail", 'failed');
      }
  }
}