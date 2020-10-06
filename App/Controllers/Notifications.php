<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Notification;

class Notifications extends \Core\Controller {

    public function viewAction() {
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

        Notification::seenAllNotify();

        View::renderTemplate('Notification/view.html', [
            'page' => 'notification',
            'notifications' => $allNotify,
            'friendsRrequests' =>  $allRequests
        ]);
    }
}

?>