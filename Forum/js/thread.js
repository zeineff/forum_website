$(document).ready(function(){
    
    // Creats a new comment and updates the page to include it
    $("#create_comment_button").click(function(e){
        e.preventDefault();
        
        $.ajax({
            method: "post",
            url: "ajax/create_comment.php",
            data: $("#create_comment_form").serialize(),
            success: function(data){
                if (data === "Comment successfully created")
                    $.ajax({
                        method: "post",
                        url: "includes/comments.php",
                        data: {
                            "thread_id": $("#cc_thread_id").val()
                        },
                        success: function(data){
                            $("#comments").html(data);
                        }
                    });
            }
        });
    });
});