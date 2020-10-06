<?php

namespace App\Controllers;

use \Core\View;

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

        //// get notification /////
        $notifications =  $this->getNotifications();
        $allNotify = $notifications['allNotify'];
        $countNotify = $notifications['count'];

        View::renderTemplate('Messages/view.html', [
            'page' => 'messages',
            'friendsRrequests' =>  $allRequests,
            'countRequest' => $countRequest,
            'notifications' => $allNotify,
            'countNotify' => $countNotify
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

        //// get notification /////
        $notifications =  $this->getNotifications();
        $allNotify = $notifications['allNotify'];
        $countNotify = $notifications['count'];

         View::renderTemplate('Messages/chat.html', [
            'page' => 'messages',
            'friendsRrequests' =>  $allRequests,
            'countRequest' => $countRequest,
            'notifications' => $allNotify,
            'countNotify' => $countNotify
        ]);
     }
}

?>