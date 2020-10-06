var url_root = 'http://localhost/spectrum';
var folder = '/spectrum';
$('.sendFriendReq').click(function() {
    var id = $(this).attr('data-id');
    sendFriendReq(id)
});

function sendFriendReq(id) {
    $.post("ajax/sendRequest",
        {
            id: id
        },
        function(response, status){
            if (status == 'success') {
                response = JSON.parse(response);
                if (response.status == true) {
                   $('.sendFriendReq[data-id="'+id+'"]').parents(".col-md-6").hide();
                
                   if ($('#sendRequests').text().trim()) {
                    var markup = `
                        <div class="alert alert-success mb-2 d-flex align-items-center">
                            <a class="alert-link d-flex align-items-center mr-2" href="${url_root}/profile/${id}"><div class="profile-icon-img-overlay-link mr-2"><img src="${folder} /public/img/${response.profile_pic}" alt="user Icon"></div> ${response.firstname} ${ response.lastname }</a>
                            <small class="text-muted ml-1">(Just now)</small>
                            <div class="ml-auto">
                                <button class="btn btn-sm btn-danger cancelFriendReq" data-id="${id}">Cancel Request</button>
                            </div>
                        </div>
                       `;

                       $('#sendRequests').append(markup);
                   } else {
                       var markup = `
                        <div class="lead mb-2">Send Request</div>
                        <div class="alert alert-success mb-2 d-flex align-items-center">
                            <a class="alert-link d-flex align-items-center mr-2" href="${url_root}/profile/${id}"><div class="profile-icon-img-overlay-link mr-2"><img src="${folder} /public/img/${response.profile_pic}" alt="user Icon"></div> ${response.firstname} ${ response.lastname }</a>
                            <small class="text-muted ml-1">(Just now)</small>
                            <div class="ml-auto">
                                <button class="btn btn-sm btn-danger cancelFriendReq" data-id="${id}">Cancel Request</button>
                            </div>
                        </div>
                       `;

                       $('#sendRequests').html(markup);
                   }

                   $('.cancelFriendReq').off().click(function() {
                        var id = $(this).attr('data-id');
                        cancelFriendReq(id);
                    });

                } else {
                    $('#failed-msg').show().fadeOut(5000);
                }
            } else {
                $('#failed-msg').show().fadeOut(5000);
            }
    });
}

$('.cancelFriendReq').click(function() {
    var id = $(this).attr('data-id');
    cancelFriendReq(id);
});

function cancelFriendReq(id) {
    $.post("ajax/cancelRequest",
        {
            id: id
        },
        function(response, status){
            if (status == 'success') {
                response = JSON.parse(response);

                if (response.status == true) {
                    $('.cancelFriendReq[data-id="'+id+'"]').parents(".alert").removeClass('d-flex').addClass('d-none');
                    var markup = `
                        <div class="col-md-6 p-3">
                            <div class="d-flex border rounded p-3">
                                <img src="${folder}/public/img/${response.profile_pic}" alt="" class="w-25 rounded">
                                <div class="ml-4 pt-2">
                                    <a href="${url_root}/profile/${id}" class="h6">${response.firstname} ${response.lastname}</a>
                                </div>
                                <div class="ml-auto"><button class="btn btn-primary btn-sm sendFriendReq" data-id="${id}">Send Request</button></div>
                            </div>                    
                        </div>
                    `; 
                    
                    $('#findFriends').append(markup);

                    $('.sendFriendReq').off().click(function() {
                        var id = $(this).attr('data-id');
                        sendFriendReq(id)
                    });
                } else {
                    $('#failed-msg').show().fadeOut(5000);
                }
            } else {
                $('#failed-msg').show().fadeOut(5000);
            }
    });
}

//// accept request and cancel request 
$('.acceptReq').click(function() {
    var id = $(this).attr('data-id');
    $.post("ajax/acceptRequest",
        {
            id: id
        },
        function(response, status){
            if (status == 'success') {
                if (response == true) {
                    $('.acceptReq[data-id="'+id+'"]').parents(".alert").removeClass('d-flex').addClass('d-none');
                } else {
                    $('#failed-msg').show().fadeOut(5000);
                }
            } else {
                $('#failed-msg').show().fadeOut(5000);
            }
    });
});

$('.declineReq').click(function() {
    var id = $(this).attr('data-id');
    $.post("ajax/declineRequest",
        {
            id: id
        },
        function(response, status){
            if (status == 'success') {
                if (response == true) {
                    $('.acceptReq[data-id="'+id+'"]').parents(".alert").removeClass('d-flex').addClass('d-none');
                } else {
                    $('#failed-msg').show().fadeOut(5000);
                }
            } else {
                $('#failed-msg').show().fadeOut(5000);
            }
    });
});