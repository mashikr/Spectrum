<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Message;

class Messages extends \Core\Controller {

    public function transferAction() {
        $this->before();

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
        $chatHolders = $messages['chatHolders'];

        //// get notification /////
        $notifications =  $this->getNotifications();
        $allNotify = $notifications['allNotify'];
        $countNotify = $notifications['count'];

        View::renderTemplate('Messages/view.html', [
            'page' => 'messages',
            'friendsRrequests' =>  $allRequests,
            'countRequest' => $countRequest,
            'notifications' => $allNotify,
            'countNotify' => $countNotify,
            'chatHolders' => $chatHolders
        ]);
    }

    public function chatAction() {
        $this->before();

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
        
        Message::seenMsg($this->route_params['id']);
        /// get chat holders
        $messages = $this->getMessages();
        $chatHolders = $messages['chatHolders'];
        $chatUser = $chatHolders[$this->route_params['id']];

        /// get message
        $messages = Message::getMessage($this->route_params['id']);
        $allmessages = [];
        if ($messages) {
            foreach ($messages as $message) {
                $message['time'] = $this->calcTime($message['time']);
                array_push($allmessages, $message);
            }
        }

        //// get notification /////
        $notifications =  $this->getNotifications();
        $allNotify = $notifications['allNotify'];
        $countNotify = $notifications['count'];

         View::renderTemplate('Messages/chat.html', [
            'page' => 'messages',
            'friendsRrequests' =>  $allRequests,
            'countRequest' => $countRequest,
            'notifications' => $allNotify,
            'countNotify' => $countNotify,
            'chatHolders' => $chatHolders,
            'messages' => $allmessages,
            'chatUser' => $chatUser
        ]);
     }
}

?>