$(document).ready(function() {
    var url_root = 'http://localhost/spectrum';
    var folder = '/spectrum';
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

        $.post(folder + "/ajax/seenAllReq",
            {
                data: 'seenAll'
            },function (response, status) {
            if (status == 'success') {
                if (response == true) {
                    $('.friend-request .badge').text('');
                } else {
                    $('#failed-msg').show().fadeOut(5000);
                }
            } else {
                $('#failed-msg').show().fadeOut(5000);
            }
        });
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

        $.post(folder + "/ajax/seenAllNotify",
            {
                data: 'seenAll'
            },function (response, status) {
            if (status == 'success') {
                if (response == true) {
                    $('.notification .badge').text('');
                } else {
                    $('#failed-msg').show().fadeOut(5000);
                }
            } else {
                $('#failed-msg').show().fadeOut(5000);
            }
        });
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
            $('.chat-emoji-body[data-target="comment-emoji"').addClass('d-none');
        }

        if (!e.target.matches('#toast-emoji, #toast-emoji *')) {
            $('.chat-emoji-body[data-target="toast-emoji"').addClass('d-none');
        }

        if (!e.target.matches('#msg-chat-emoji, #msg-chat-emoji *')) {
            $('#msg-chat-emoji .chat-emoji-body').addClass('d-none');
        }

        if (!e.target.matches('.post-option, .post-option *')) {
            $('.post-option-dropdown').addClass('d-none');
        }

        if (!e.target.matches('.image-action, .image-action *')) {
            $('.image-action .dropdown-menu').css('display', 'none');
        }


    });


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
    messageLinkEvent();

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

    $('#messageUser').click(function() {
        var user_id = $('#username').attr("data-id");
        var name = $('#username').text();
        var pic = $('.profile-img-overlay').children().children("img").attr('src');
        showToastWithMsg(user_id,name,pic);
    });

    $('#toast-close').click(function() {
        $('#msg-toast').removeClass('show-toast');
        $('#toast-emoji .chat-emoji-body').addClass('d-none');
    });
    
   /// send messages
   function sendMessage(id,message,type) {
        $.post(folder + "/ajax/sendMessage",
            {
                id: id,
                message: message,
                type: type
            },function (response, status) {
            if (status == 'success') {
                response = JSON.parse(response);
                if (response.status == true) {
                    if (response.unseen) {
                        $('.messages .badge').text(response.unseen);
                    } else {
                        $('.messages .badge').html('');
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
   $('#sendText').keyup(function(e) {
        if (e.keyCode == 13) {
            var id = $('#send-msg-toast').attr('data-id');
            var message = $(this).val();
            $(this).val('');
            sendMessage(id,message,'text');
        }
   });

   $('#sendImage').change(function() {
        $.ajax({
            type: 'POST',
            url: folder + '/ajax/sendImage',
            data: new FormData ($('#sendImageForm')[0]),
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.includes("<b>Warning</b>:  POST Content-Length of")) {
                    $('#failed-msg').html('<div class="alert alert-danger"><strong>File sixe limit exceed!</strong></div>').show().fadeOut(5000);
                } else {
                    response = JSON.parse(response)
                    if (response.status == true) {
                        if (response.unseen) {
                            $('.messages .badge').text(response.unseen);
                        } else {
                            $('.messages .badge').html('');
                        }
                        $('#messagesDropdown').html(response.chatHolder);
                        $('#chat-body').html(response.messages);
                        scrollToastChatBody();
                        messageLinkEvent();
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

    $('#sendFile').change(function() {
        $.ajax({
            type: 'POST',
            url: folder + '/ajax/sendFile',
            data: new FormData ($('#sendFileForm')[0]),
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.includes("<b>Warning</b>:  POST Content-Length of")) {
                    $('#failed-msg').html('<div class="alert alert-danger"><strong>File sixe limit exceed!</strong></div>').show().fadeOut(5000);
                } else {
                    response = JSON.parse(response)
                    if (response.status == true) {
                        if (response.unseen) {
                            $('.messages .badge').text(response.unseen);
                        } else {
                            $('.messages .badge').html('');
                        }
                        $('#messagesDropdown').html(response.chatHolder);
                        $('#chat-body').html(response.messages);
                        scrollToastChatBody();
                        messageLinkEvent();
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

    //// toggle emoji body ////
    $('.chat-emoji').off().click(function() {
        var target = $(this).attr('data-target');
        var id = $(this).attr('data-id');
        if (target == 'toast-emoji') {
            $(this).parent().children('.chat-emoji-body').html(emojis).toggleClass('d-none');
            $(this).parent().children('.chat-emoji-body').children('.emoji').off().click(function() {
                var id = $('#send-msg-toast').attr('data-id');
                var message = $(this).attr('src');
                $('.chat-emoji-body[data-target="toast-emoji"').addClass('d-none');
                sendMessage(id,message,'emoji');
            });
        } else if(target == 'comment-emoji') {
            $(this).parent().children('.chat-emoji-body').html(emojis).toggleClass('d-none');
            $(this).parent().children('.chat-emoji-body').children('.emoji').off().click(function() {
                var emoji = $(this).attr('src');
                $.post(folder + "/ajax/commentEmoji",
                    {
                        id: id,
                        emoji: emoji
                    },function (response, status) {
                    if (status == 'success') {
                        if (response == true) {
                            $('.comment-emoji .chat-emoji-body').addClass('d-none');
                            var count = parseInt($('.comment-count[data-id="'+id+'"]').text());
                            $('.comment-count[data-id="'+id+'"]').text(count + 1);

                            if ($('#post'+ id).attr('page') == 'post') {
                                getComments(id);
                            }
                        } else {
                            $('#failed-msg').show().fadeOut(5000);
                        }
                    } else {
                        $('#failed-msg').show().fadeOut(5000);
                    }
                });
            });
        } else {
            $('#msg-chat-emoji .chat-emoji-body').html(emojis).toggleClass('d-none');
            $('#msg-chat-emoji .emoji').off().click(function() {
                var message = $(this).attr('src');
                var id = $('#chatUser').attr('data-id');
                $('#msg-chat-emoji .chat-emoji-body').toggleClass('d-none');
                
                $.post(folder + "/ajax/sendMessage",
                    {
                        id: id,
                        message: message,
                        type: 'emoji',
                        page: 'msg'
                    },function (response, status) {
                    if (status == 'success') {
                        response = JSON.parse(response);
                        if (response.status == true) {
                            $('#chatHolders').html(response.chatHolder);
                            $('#message-body').html(response.messages);
                            $('#message-body').scrollTop($('#message-body')[0].scrollHeight);
                        } else {
                            $('#failed-msg').show().fadeOut(5000);
                        }
                    } else {
                        $('#failed-msg').show().fadeOut(5000);
                    }
                });
            });
        }
            
    });

    /// like - unlike a  POST ///
    $('.like-btn').click(function() {
        var post_id = $(this).attr('data-id');
        var isLiked = $(this).attr('post-like');

        if (isLiked == 1) {
            $.post(folder + "/ajax/unlikePost",
                {
                    id: post_id 
                },function (response, status) {
                if (status == 'success') {
                    if (response == true) {
                        $('.like-btn[data-id="'+post_id+'"]').attr('post-like', '0').removeClass('text-primary').children('.fas').removeClass('fas').addClass('far');
                        var count = parseInt($('.like-count[data-id="'+post_id+'"]').text());
                        $('.like-count[data-id="'+post_id+'"]').text(count - 1);
                    } else {
                        $('#failed-msg').show().fadeOut(5000);
                    }
                } else {
                    $('#failed-msg').show().fadeOut(5000);
                }
            });
        } else {
            $.post(folder + "/ajax/likePost",
                {
                    id: post_id 
                },function (response, status) {
                if (status == 'success') {
                    console.log(response);
                    if (response == true) {
                        $('.like-btn[data-id="'+post_id+'"]').attr('post-like', '1').addClass('text-primary').children('.far').removeClass('far').addClass('fas');
                        var count = parseInt($('.like-count[data-id="'+post_id+'"]').text());
                        $('.like-count[data-id="'+post_id+'"]').text(count + 1);
                    } else {
                        $('#failed-msg').show().fadeOut(5000);
                    }
                } else {
                    $('#failed-msg').show().fadeOut(5000);
                }
            });
        }
    });

    //// comments in a post ////
    $('.comment-text').keyup(function(e) {
       if (e.keyCode == 13) {
            var post_id = $(this).attr('data-id');
            var comment = $(this).val();
            $(this).val('');
            
            $.post(folder + "/ajax/commentText",
                {
                    id: post_id,
                    comment: comment
                },function (response, status) {
                if (status == 'success') {
                    if (response == true) {
                        var count = parseInt($('.comment-count[data-id="'+post_id+'"]').text());
                        $('.comment-count[data-id="'+post_id+'"]').text(count + 1);

                        if ($('#post'+ post_id).attr('page') == 'post') {
                            getComments(post_id);
                        }
                    } else {
                        $('#failed-msg').show().fadeOut(5000);
                    }
                } else {
                    $('#failed-msg').show().fadeOut(5000);
                }
            });
       }
    });

    function getComments(post_id) {
        $.post(folder + "/ajax/getComments",
            {
                id: post_id,
            },function (response, status) {
            if (status == 'success') {
                if (response != false) {
                    $('#post-comments').html(response);
                    deleteComment();
                } else {
                    $('#failed-msg').show().fadeOut(5000);
                }
            } else {
                $('#failed-msg').show().fadeOut(5000);
            }
        });
    }

    $('.comment-image-input').change(function() {
        var post_id = $(this).attr('data-id');
        $.ajax({
            type: 'POST',
            url: folder + '/ajax/commentPhoto',
            data: new FormData ($(this).parent()[0]),
            contentType: false,
            processData: false,
            success: function (data) {
                if (data == true) {
                    var count = parseInt($('.comment-count[data-id="'+post_id+'"]').text());
                    $('.comment-count[data-id="'+post_id+'"]').text(count + 1);

                    if ($('#post'+ post_id).attr('page') == 'post') {
                        getComments(post_id);
                    }
                } else if (data == false) {
                    $('#failed-msg').show().fadeOut(5000);
                } else if (data == 'invalid') {
                    $('#failed-msg').html('<div class="alert alert-danger"><strong>Invalid file!</strong></div>').show().fadeOut(5000);
                } else {
                    $('#failed-msg').html('<div class="alert alert-danger"><strong>File size limit exceed!</strong></div>').show().fadeOut(5000);
                }
            }
        });
        $('#failed-msg').html('<div class="alert alert-danger"><strong>Something wrong!</strong></div>');
    });

    /// delete a comment
    function deleteComment() {
        $('.deleteComment').off().click(function() {
            var id = $(this).attr('data-id');
            var post_id =  $(this).attr('data-post');
            var r = confirm("Are you sure, want delete?");
            if (r == true) {
                $.post(folder + "/ajax/deleteComment",
                    {
                        id: id,
                    },function (response, status) {
                    if (status == 'success') {
                        if (response != false) {
                            $('#post-comments').html(response);
                            var count = parseInt($('.comment-count[data-id="'+post_id+'"]').text());
                            $('.comment-count[data-id="'+post_id+'"]').text(count - 1);
                            deleteComment();
                        } else {
                            $('#failed-msg').show().fadeOut(5000);
                        }
                    } else {
                        $('#failed-msg').show().fadeOut(5000);
                    }
                });
            }
        });    
    }
    deleteComment();

    /// like and comment list ///
    $('.like-list').click(function() {
       var post_id = $(this).attr('data-post');
       var likes = parseInt($(this).children('.like-count').text());
       if (likes) {
            $('#like-comment-modal').modal('show');
            $('#like-comment-modal-label').text('Likes');
            $('#like-comment-modal .modal-body').html('<i class="fas fa-spinner fa-spin fa-2x"></i>');

            $.post(folder + "/ajax/getLikes",
                {
                    id: post_id,
                },function (response, status) {
                if (status == 'success') {
                    if (response != false) {
                        $('#like-comment-modal .modal-body').html(response);
                    } else {
                        $('#failed-msg').show().fadeOut(5000);
                    }
                } else {
                    $('#failed-msg').show().fadeOut(5000);
                }
            });

       } else {
        $('#failed-msg').show().fadeOut(5000);
       }
    });

    $('.comment-list').click(function() {
        var post_id = $(this).attr('data-post');
        var comments = parseInt($(this).children('.comment-count').text());
        if (comments) {
            $('#like-comment-modal').modal('show');
            $('#like-comment-modal-label').text('Comments');
            $('#like-comment-modal .modal-body').html('<i class="fas fa-spinner fa-spin fa-2x"></i>');

            $.post(folder + "/ajax/getComments",
                {
                    id: post_id,
                },function (response, status) {
                if (status == 'success') {
                    if (response) {
                        $('#like-comment-modal .modal-body').html(response);
                        $('#like-comment-modal .modal-body .deleteComment').hide();
                    } else {
                        $('#failed-msg').show().fadeOut(5000);
                    }
                } else {
                    $('#failed-msg').show().fadeOut(5000);
                }
            });
        } else {
         $('#failed-msg').show().fadeOut(5000);
        }
    });

    /// search input
    $('#searchInput').keyup(function() {
        var key = $(this).val().trim();
        if (key) {
            $.post(folder + "/ajax/search",
                {
                    key: key,
                },function (response, status) {
                if (status == 'success') {
                    if (response) {
                       response = JSON.parse(response);
                       $('#searchList').html(response.searchList);
                    } else {
                        $('#searchList').html('');
                    }
                } else {
                    $('#searchList').html('');
                }
            });
        } else {
            $('#searchList').html('');
        }
    });

    
    ////// post action //////
    $('.post-option').click(function() {
        $(this).children('.post-option-dropdown').toggleClass('d-none');
    });
    //// flash msg fade out //////
    $('.alert-dismissable').fadeOut(5000);

    /// tost chat body scroll ///
    function scrollToastChatBody() {
        $('#chat-body').animate({scrollTop: $('#chat-body')[0].scrollHeight});
    }
});