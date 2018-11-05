$(document).ready(function(){
    
    // Updates error messages on the upload avatar form
    // Uploads the avatar if none are found
    $("#upload_avatar_form").on("submit", (function(e){
        e.preventDefault();
        
        $.ajax({
            method: "post",
            url: "ajax/upload_avatar.php",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data){
                $("#upload_avatar_error").html(data);
            }
        });
    }));
});