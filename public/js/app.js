$(document).ready(function() {
    var emojis = `
            <img src="/spectrum/public/emoji/emoji-1.png" alt="imoji-1" class="emoji">
            <img src="/spectrum/public/emoji/emoji-2.png" alt="imoji-2" class="emoji">
            <img src="/spectrum/public/emoji/emoji-3.png" alt="imoji-3" class="emoji">
            <img src="/spectrum/public/emoji/emoji-4.png" alt="imoji-4" class="emoji">
            <img src="/spectrum/public/emoji/emoji-5.png" alt="imoji-5" class="emoji">
            <img src="/spectrum/public/emoji/emoji-6.png" alt="imoji-6" class="emoji">
            <img src="/spectrum/public/emoji/emoji-7.png" alt="imoji-7" class="emoji">
            <img src="/spectrum/public/emoji/emoji-8.png" alt="imoji-8" class="emoji">
            <img src="/spectrum/public/emoji/emoji-9.png" alt="imoji-9" class="emoji">
            <img src="/spectrum/public/emoji/emoji-10.png" alt="imoji-10" class="emoji">
            <img src="/spectrum/public/emoji/emoji-11.png" alt="imoji-11" class="emoji">
            <img src="/spectrum/public/emoji/emoji-12.png" alt="imoji-12" class="emoji">
            <img src="/spectrum/public/emoji/emoji-13.png" alt="imoji-13" class="emoji">
            <img src="/spectrum/public/emoji/emoji-14.png" alt="imoji-14" class="emoji">
            <img src="/spectrum/public/emoji/emoji-15.png" alt="imoji-15" class="emoji">
            <img src="/spectrum/public/emoji/emoji-16.png" alt="imoji-16" class="emoji">
    `;

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

    /// body event listener ///////
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
        if (!e.target.matches('.comment-emoji, .comment-emoji *')) {
            $('.chat-emoji-body').addClass('d-none');
        }

        if (!e.target.matches('.post-option, .post-option *')) {
            $('.post-option-dropdown').addClass('d-none');
        }


    });


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
    
   

    //// toggle emoji body ////
    $('.chat-emoji').click(function() {
        var target = $(this).attr('data-target');
        if (target == 'toast-emoji') {
            $('#toast-emoji .chat-emoji-body').html(emojis).toggleClass('d-none');
            $('#toast-emoji .emoji').off();
            $('#toast-emoji .emoji').click(function() {
                console.log('toast');
                console.log($(this).attr('src'));
            });
        } else if(target == 'comment-emoji') {
            $(this).parent().children('.chat-emoji-body').html(emojis).toggleClass('d-none');
            $(this).parent().children('.chat-emoji-body').children('.emoji').off();
            $(this).parent().children('.chat-emoji-body').children('.emoji').click(function() {
                console.log('comment');
                console.log($(this).attr('src'));
            });
        } else {
            $('#msg-chat-emoji .chat-emoji-body').html(emojis).toggleClass('d-none');
            $('#msg-chat-emoji .emoji').off();
            $('#msg-chat-emoji .emoji').click(function() {
                console.log('chat');
                console.log($(this).attr('src'));
            });
        }
            
    });

    //// flash msg fade out //////
    $('.alert-dismissable').fadeOut(5000);

    /// tost chat body scroll ///
   function scrollToastChatBody() {
        $('#chat-body').scrollTop($('#chat-body')[0].scrollHeight);
   }

});