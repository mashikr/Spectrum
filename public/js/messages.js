var folder = '/spectrum';
// send message
$('#sendTextMsg').keyup(function(e) {
    if (e.keyCode == 13) {
        var id = $('#chatUser').attr('data-id');
        var message = $(this).val();
        $(this).val('');

        $.post(folder + "/ajax/sendMessage",
            {
                id: id,
                message: message,
                type: 'text',
                page: 'msg'
            },function (response, status) {
            if (status == 'success') {
                response = JSON.parse(response);
                if (response.status == true) {
                    $('#chatHolders').html(response.chatHolder);
                    $('#message-body').html(response.messages);
                    scrollMessageChatBody();
                } else {
                    $('#failed-msg').show().fadeOut(5000);
                }
            } else {
                $('#failed-msg').show().fadeOut(5000);
            }
        });
    }
});

$('#sendImageMsg').change(function() {
    $.ajax({
        type: 'POST',
        url: folder + '/ajax/sendImage',
        data: new FormData ($('#sendImageMsgFrom')[0]),
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.includes("<b>Warning</b>:  POST Content-Length of")) {
                $('#failed-msg').html('<div class="alert alert-danger"><strong>File sixe limit exceed!</strong></div>').show().fadeOut(5000);
            } else {
                response = JSON.parse(response)
                if (response.status == true) {
                    $('#chatHolders').html(response.chatHolder);
                    $('#message-body').html(response.messages);
                    scrollMessageChatBody();
                } else if (response.status == false) {
                    if (response.msg = 'invalid') {
                        $('#failed-msg').html('<div class="alert alert-danger"><strong>This file isn\'t valid!</strong></div>').show().fadeOut(5000);
                    } else {
                        $('#failed-msg').html('<div class="alert alert-danger"><strong>Image send failed!</strong></div>').show().fadeOut(5000);
                    }
                }
            }
        }
    });
    $('#failed-msg').html('<div class="alert alert-danger"><strong>Something went wrong!</strong></div>');
});

$('#sendFileMsg').change(function() {
    $.ajax({
        type: 'POST',
        url: folder + '/ajax/sendFile',
        data: new FormData ($('#sendFileMsgForm')[0]),
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.includes("<b>Warning</b>:  POST Content-Length of")) {
                $('#failed-msg').html('<div class="alert alert-danger"><strong>File sixe limit exceed!</strong></div>').show().fadeOut(5000);
            } else {
                response = JSON.parse(response)
                if (response.status == true) {
                    $('#chatHolders').html(response.chatHolder);
                    $('#message-body').html(response.messages);
                    scrollMessageChatBody();
                } else if (response.status == false) {
                    if (response.msg = 'invalid') {
                        $('#failed-msg').html('<div class="alert alert-danger"><strong>This file isn\'t valid!</strong></div>').show().fadeOut(5000);
                    } else {
                        $('#failed-msg').html('<div class="alert alert-danger"><strong>Image send failed!</strong></div>').show().fadeOut(5000);
                    }
                }
            }
        }
    });
    $('#failed-msg').html('<div class="alert alert-danger"><strong>Something went wrong!</strong></div>');
});

/// message chat body scroll ///
function scrollMessageChatBody() {
    $('#message-body').animate({scrollTop: $('#message-body')[0].scrollHeight});
}
scrollMessageChatBody();