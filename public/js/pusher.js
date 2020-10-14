var folder = '/spectrum';
function soundNotify() {
    const audio = new Audio('/spectrum/public/notificationSound/notification.mp3');
    window.focus();
    audio.play();
}
function soundMsg() {
    const audio = new Audio('/spectrum/public/notificationSound/msg.mp3');
    window.focus();
    audio.play();
}
function friendReqPush(data) {
    if (data.count) {
        $('.friend-request .badge').text(data.count);
    } else {
        $('.friend-request .badge').text('');
    }
    if (data.sender) {
        $('#notify-msg .alert').html('<strong>' + data.sender + '</strong> send you a friend request');
        $('#notify-msg').show().fadeOut(5000);
        soundNotify();
    }
    $('#reqDropdown').html(data.reqdropdown);
}

function notificationPush(data) {
    if (data.count) {
        $('.notification .badge').text(data.count);
    } else {
        $('.notification .badge').text('');
    }
    if (data.sender) {
        var markup = '<strong>' + data.sender + '</strong> ';

        if (data.category == 'likes') {
            markup += 'likes your ';
        } else if (data.category == 'comments') {
            markup += 'comments on your ';
        } else {
            markup += 'accept your friend request';
        }

        if (data.type == 'profile_pic') {
            markup += 'profile photo';
        } else if (data.type == 'cover_pic') {
            markup += 'cover photo';
        } else if (data.type) {
            markup += data.type;
        }

        $('#notify-msg .alert').html(markup);
        $('#notify-msg').show().fadeOut(5000);
        soundNotify();
    }
    $('#notifyDropdown').html(data.notifydropdown);
}

function messagePush(data) {
    if ($('.messages').length) {
        var id = $('#send-msg-toast').attr('data-id');
        if (id && id == data.sender) {
            $.post(folder + "/ajax/getToastChat",
                {
                    message: true,
                    id: data.sender
                },function (response, status) {
                if (status == 'success') {
                    response = JSON.parse(response);
                    if (response.status == true) {
                        if (response.count) {
                            $('.messages .badge').text(response.count);
                        } else {
                            $('.messages .badge').html('');
                        }
                        $('#messagesDropdown').html(response.chatHolder);
                        $('#chat-body').html(response.messages);
                        scrollToastChatBody();
                        soundMsg();
                        messageLinkEvent();
                    }
                }
            });
        } else {
            $.post(folder + "/ajax/getToastChat",
                {
                    message: false
                },function (response, status) {
                if (status == 'success') {
                    response = JSON.parse(response);
                    if (response.status == true) {
                        if (response.count) {
                            $('.messages .badge').text(response.count);
                        } else {
                            $('.messages .badge').html('');
                        }
                        $('#messagesDropdown').html(response.chatHolder);
                        soundMsg();
                        messageLinkEvent();
                    }
                }
            });
        }
       
    } else {
        var id = $('#chatUser').attr('data-id');
        if (id && id == data.sender) {
            $.post(folder + "/ajax/getChat",
                {
                    message: true,
                    id: data.sender
                },function (response, status) {
                if (status == 'success') {
                    response = JSON.parse(response);
                    if (response.status == true) {
                        $('#chatHolders').html(response.chatHolder);
                        $('#message-body').html(response.messages);
                        scrollMessageChatBody();
                        soundMsg();
                    }
                }
            });
        } else {
            $.post(folder + "/ajax/getChat",
                {
                    message: false,
                },function (response, status) {
                if (status == 'success') {
                    response = JSON.parse(response);
                    if (response.status == true) {
                        $('#chatHolders').html(response.chatHolder);
                        soundMsg();
                    }
                }
            });
        }
    }
}

////// chat toast toggle ////
function messageLinkEvent() {
    $('.message-link').off().click(function() {
        var user_id = $(this).attr("data-id");
        var name = $(this).children().children("b").text();
        var pic = $(this).children().children("img").attr('src');
        
        $('.msg-user-id').val(user_id);
        $('#messages-dropdown').removeClass('top-3');
        showToastWithMsg(user_id,name,pic);
    });
}
function showToastWithMsg(user_id,name,pic) {
    $('#msg-toast').addClass('show-toast');
    $('.toast-header').children().children('img').attr('src', pic);
    $('.toast-header').children('strong').text(name);
    $('#send-msg-toast').attr('data-id', user_id);
    
    $('#sendText').val('');
    $('#chat-body').html('<div class="text-center mt-5"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');

    $.post(folder + "/ajax/getMessage",
        {
            id: user_id 
        },function (response, status) {
        if (status == 'success') {
            response = JSON.parse(response);
            if (response.status == true) {
                if (response.unseen) {
                    $('#messageUnseen').text(response.unseen);
                } else {
                    $('#messageUnseen').text('');
                }
                $('#messagesDropdown').html(response.chatHolder);
                $('#chat-body').html(response.messages);

                scrollToastChatBody();
                messageLinkEvent();
            } else {
                $('#failed-msg').show().fadeOut(5000);
            }
        } else {
            $('#failed-msg').show().fadeOut(5000);
        }
    });
}

/// tost chat body scroll ///
function scrollToastChatBody() {
    $('#chat-body').animate({scrollTop: $('#chat-body')[0].scrollHeight});
}

/// message chat body scroll ///
function scrollMessageChatBody() {
    $('#message-body').animate({scrollTop: $('#message-body')[0].scrollHeight});
}