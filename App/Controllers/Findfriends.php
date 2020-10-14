<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Findfriend;
use \App\Models\User;
use \App\Models\Notification;

class Findfriends extends \Core\Controller {

    public function responseAction() {
        $this->before();

        // get friend requests ////
        $requests_ids = $this->getRequest(true);
        $sendRequests = Findfriend::getSendRequests();
        $friends = User::getFriendList();

        $allRequests = $requests_ids['requests'];
        $friendReqId = $requests_ids['ids'];
        $allsendRequests = [];
        $sendRequestsId = [];

        /// seen = 1 for all request
        if ($allRequests) {
            Findfriend::seenAllReq();
        }

        if ($sendRequests) {
            foreach ($sendRequests as $sendRequest) {
                $sendRequest['time'] = $this->calcTime($sendRequest['date-time']);
                array_push($allsendRequests, $sendRequest);
                array_push($sendRequestsId, $sendRequest['receiver']);
            }
        }

        //print_r($allsendRequests);

        if ($friends && $friendReqId) {
            $friends =   $friends . "," . implode(",",$friendReqId);
        } else if (empty($friends) && $friendReqId) {
            $friends =   $friends . implode(",",$friendReqId);
        }

        if ($friends && $sendRequestsId) {
            $friends =   $friends . "," . implode(",",$sendRequestsId);
        } else if (empty($friends) && $sendRequestsId) {
            $friends =   $friends . implode(",",$sendRequestsId);
        }

        /// get suggest friend list ///    
        $notFriendslist = Findfriend::findFriend($friends);
        $notFriends = [];

        $mutual = 1;

        if ($friends == '') {
            $mutual = 0;
        }
        $friends = explode(",", $friends);
        foreach ($notFriendslist as $notFriend) {

            if ($mutual == 0 || empty($notFriend['friends'])) {
                $notFriend['friends'] = 'No';
            } else {
                $notFriendsFiend = explode(",", $notFriend['friends']);

                $mutualFriends = count(array_intersect($friends,$notFriendsFiend));
                if ($mutualFriends) {
                    $notFriend['friends'] = $mutualFriends;
                } else {
                    $notFriend['friends'] = 'No';
                }
            }
            array_push($notFriends, $notFriend);
        }

        /// get messages
        $messages = $this->getMessages();
        $unseenMsg = $messages['unseen'];
        $chatHolders = $messages['chatHolders'];

        //// get notification /////
        $notifications =  $this->getNotifications();
        $allNotify = $notifications['allNotify'];
        $countNotify = $notifications['count'];
        
        View::renderTemplate('Findfriends/response.html', [
            'page' => 'findfriends',
            'requests' => $allRequests,
            'sendRequests' => $allsendRequests,
            'suggest' => $notFriends,
            'notifications' => $allNotify,
            'unseenMsg' => $unseenMsg,
            'chatHolders' => $chatHolders
        ]);
    }
    
    public function sendRequestAction() {
        $this->before();
        $id = $this->route_params['id'];
        Findfriend::sendRequest($id);

        /// get friends requests ////
        $Requests = $this->getRequest($id);
        $allRequests = $Requests['requests'];
        $countRequest = 0;
        if ($allRequests) {
          foreach ($allRequests as $request) {
            if ($request['seen'] == 0) {
              $countRequest++;
            }
          }
        }

        $data = [
            'sender' =>  $_SESSION['user']['name'],
            'count' =>  $countRequest,
            'reqdropdown' => View::getTemplate("Navbar/friendReq.html", ['friendsRrequests' => $allRequests])
        ];
        
        $this->pusher->trigger('friendReq', 'to-'.$id, $data);

        $this->redirect("/profile/$id");
    }
    
    public function cancelRequestAction() {
        $this->before();

        $id = $this->route_params['id'];
        Findfriend::cancelRequest($id);

        /// get friends requests ////
        $Requests = $this->getRequest($id);
        $allRequests = $Requests['requests'];
        $countRequest = 0;
        if ($allRequests) {
          foreach ($allRequests as $request) {
            if ($request['seen'] == 0) {
              $countRequest++;
            }
          }
        }

        $data = [
            'count' =>  $countRequest,
            'reqdropdown' => View::getTemplate("Navbar/friendReq.html", ['friendsRrequests' => $allRequests])
        ];
        
        $this->pusher->trigger('friendReq', 'to-'.$id, $data);

        $this->redirect("/profile/$id");
    }

    public function addFriendAction() {
        $this->before();

        $id = $this->route_params['id'];

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

        Findfriend::declineRequest($id);

        Notification::acceptReqNotify($id);

         ////// get notification /////
        $notifications =  $this->getNotifications($id);
        $allNotify = $notifications['allNotify'];
        $countNotify = $notifications['count'];

        $data = [
            'sender' =>  $_SESSION['user']['name'],
            'category' => 'friendReq',
            'count' =>  $countNotify,
            'notifydropdown' => View::getTemplate("Navbar/notificationDropdown.html", ['notifications' => $allNotify])
        ];
        $this->pusher->trigger('notification', 'to-'.$id, $data);

        $this->redirect("/profile/$id");
    }

    public function declineAction() {
        $this->before();

        $id = $this->route_params['id'];
        Findfriend::declineRequest($id);

        $this->redirect("/profile/$id");
    }

    public function unfriendAction() {
        $this->before();

        $id = $this->route_params['id'];

        /// get current user friend list
        $cur_user_friends =  User::getFriendList();

        if (strpos($cur_user_friends ,"$id")) {
            $cur_user_friends = str_replace(",$id", "", $cur_user_friends);
        } else {
            $cur_user_friends = ltrim(str_replace("$id", "", $cur_user_friends), ',');
        }
        Findfriend::addFriend($_SESSION['user_id'], $cur_user_friends);

        //// get req sender friend list
        $friends =  User::getFriendList($id);
        $cur_user_id = $_SESSION['user_id'];
        if (strpos($friends ,"$cur_user_id")) {
            echo $friends = str_replace(",$cur_user_id", "", $friends);
        } else {
            $friends = ltrim(str_replace("$cur_user_id", "", $friends), ',');
        }
        Findfriend::addFriend($id, $friends);

        Notification::updateFriendReqNotify($id);
;
        $this->redirect("/profile/$id");
    }
}

?>