$(document).ready(function() {

////// dropdown toggler
    $('#friend-request').click(function() {
        $('#friend-request-dropdown').toggleClass('top-3');
        $('#messages-dropdown').removeClass('top-3');
        $('#notification-dropdown').removeClass('top-3');
    });

    $('#messages').click(function() {
        $('#messages-dropdown').toggleClass('top-3');
        $('#friend-request-dropdown').removeClass('top-3');
        $('#notification-dropdown').removeClass('top-3');
    });

    $('#notification').click(function() {
        $('#notification-dropdown').toggleClass('top-3');
        $('#messages-dropdown').removeClass('top-3');
        $('#friend-request-dropdown').removeClass('top-3');
    });

    $('body').click(function(e) {

        if (!e.target.matches('#friend-request, #friend-request *') && !e.target.matches('#friend-request-dropdown, #friend-request-dropdown *')) {
            if ($('#friend-request-dropdown').css('top') == '48px') {
                $('#friend-request-dropdown').removeClass('top-3');
            }
        }

        if (!e.target.matches('#messages, #messages *') && !e.target.matches('#messages-dropdown, #messages-dropdown *')) {
            if ($('#messages-dropdown').css('top') == '48px') {
                $('#messages-dropdown').removeClass('top-3');
            }
        }

        if (!e.target.matches('#notification, #notification *') && !e.target.matches('#notification-dropdown, #notification-dropdown *')) {
            if ($('#notification-dropdown').css('top') == '48px') {
                $('#notification-dropdown').removeClass('top-3');
            }
        }


    })

    ////// chat toast toggle ////
    $('.message-link').click(function() {
        $('#msg-toast').addClass('show-toast');
        $('#messages-dropdown').removeClass('top-3');
        
        scrollToastChatBody();
    });

    $('#toast-close').click(function() {
        $('#msg-toast').removeClass('show-toast');
        $('#toast-emoji .chat-emoji-body').addClass('d-none');
    });
    
    /// custom file name
    $('.custom-file-input').change(function(e){
        var fileName = e.target.files[0].name;
        $('.custom-file-label').html(fileName);
    });

    /// image action ////
    $('.image-action-toggler').click(function() {
        var target = $(this).attr('data-img');

        if ($('#' + target).css('display') == 'none') {
            $('.dropdown-menu').hide();
            $('#' + target).show();
        } else {
            $('#' + target).hide();
        }
    });

    ///// profile photos update btn /////////

    $('.profile-pic-update-btn').click(function() {
        $('#photoUpdateModal').modal('show');
        $('.modal-title').text('Update Profile Photo')

    });

    $('.cover-pic-update-btn').click(function() {
        $('#photoUpdateModal').modal('show');
        $('.modal-title').text('Update Cover Photo')

    });

    ///// update about /////////
    $('.about-edit-btn').click(function() {
        var target = $(this).attr('data-target');
        
        $('#aboutUpdateModal').modal('show');
        $('#aboutUpdateModalTitle').text();
        $('#aboutInput').val($('#'+target).text().trim());

        $('#update-about-btn').click(function() {
            console.log('test');
        });
    });

    //// toggle emoji body ////
    $('.chat-emoji').click(function() {
        var target = $(this).attr('data-target');
        if (target) {
            $('#toast-emoji .chat-emoji-body').toggleClass('d-none');
            $('#toast-emoji .emoji').off();
            $('#toast-emoji .emoji').click(function() {
                console.log('toast');
                console.log($(this).attr('src'));
            });
        } else {
            $('#msg-chat-emoji .chat-emoji-body').toggleClass('d-none');
            $('#msg-chat-emoji .emoji').off();
            $('#msg-chat-emoji .emoji').click(function() {
                console.log('chat');
                console.log($(this).attr('src'));
            });
        }
            
    });

    /// tost chat body scroll ///
   function scrollToastChatBody() {
        $('#chat-body').scrollTop($('#chat-body')[0].scrollHeight);
   }
   
    /// message chat body scroll ///
   function scrollMessageChatBody() {
        $('#message-body').scrollTop($('#message-body')[0].scrollHeight);
   }
   scrollMessageChatBody();

});