 /// custom file name
 $('.custom-file-input').change(function(e){
    var fileName = e.target.files[0].name;
    $('.custom-file-label').html(fileName);
});

/// like and comment list ///
$('.like-list').click(function() {
    console.log($(this).attr('data-post'));
})

$('.comment-list').click(function() {
    console.log($(this).attr('data-post'));
});

