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
        $('#photo-type').val('profile_pic')
    });

    $('.cover-pic-update-btn').click(function() {
        $('#photoUpdateModal').modal('show');
        $('.modal-title').text('Update Cover Photo')
        $('#photo-type').val('cover_pic')
    });

     ///// update photo ////////
    $('#update-photo').change(function() {
        $.ajax({
            type: 'POST',
            url: 'ajax/updatePhoto',
            data: new FormData ($('#update-photo-form')[0]),
            contentType: false,
            processData: false,
            success: function ( data ) {
                if (data == true) {
                   window.location = 'http://localhost/spectrum/profile';
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

    ///// update about /////////
    $('.about-edit-btn').click(function() {
        var target = $(this).attr('data-target');
        
        $('#aboutUpdateModal').modal('show');
        $('#aboutUpdateModalTitle').text('Update ' + target.replace('-',' '));
        $('#aboutInput').val($('#'+target).text().trim());

        $('#update-about-btn').click(function() {
            var data = $('#aboutInput').val().trim();
            $.post("ajax/updateAbout",
                {
                    type: target,
                    data: data
                },
                function(response, status){
                   if (status == 'success') {
                       if (response == true) {
                        $('#'+target).text(data);
                        
                       } else {
                            $('#failed-msg').show().fadeOut(5000);
                       }
                   } else {
                        $('#failed-msg').show().fadeOut(5000);
                   }
                   $('#aboutUpdateModal').modal('hide');
            });
            
            $('#update-about-btn').off();
        });
    });

   

    ////// post action //////
    $('.post-option').click(function() {
        $(this).children('.post-option-dropdown').toggleClass('d-none');
    });

    /// like and comment list ///
    $('.like-list').click(function() {
        console.log($(this).attr('data-post'));
    });

    $('.comment-list').click(function() {
        console.log($(this).attr('data-post'));
    });