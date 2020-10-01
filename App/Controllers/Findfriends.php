<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Findfriend;
use \App\Models\User;

class Findfriends extends \Core\Controller {

    public function responseAction() {
        $this->before();

        $friends = User::getFriendList();
        
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
                $notFriend['friends'] = $mutualFriends;
            }
            array_push($notFriends, $notFriend);
        }
        
        View::renderTemplate('Findfriends/response.html', [
            'page' => 'findfriends',
            'suggest' => $notFriends
        ]);
    }
}

?>