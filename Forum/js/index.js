$(document).ready(function(){
    var logged_in = false;
    
    // Checks if the user is logged in
    $.ajax({
        method: "post",
        url: "ajax/logged_in.php",
        async: false,
        success: function(data){
            if (data === "True")
                logged_in = true;
            else
                logged_in = false;
        }
    });
    
    
    // Updates error messages on the create thread form
    // Submits the form if no errors are found
    $("#create_thread_button").click(function(e){
        e.preventDefault();
        
        var error = false;
        var title = $("#ctf_title").val();
        var title_error = $("#ctf_title_error");
        var content_error = $("#ctf_content_error");
        
        if (title === ""){  // Title cannot be blank
            title_error.html("*Required").slideDown("fast");
            error = true;
        }else{
            title_error.slideUp("fast");
            
            // Checks if the given thread title is already in use
            $.ajax({
                method: "post",
                url: "ajax/thread_title_taken.php",
                async: false,
                data: {
                    "title": title
                },
                success: function(data){
                    if (data === "True"){  // Title is already taken
                        title_error.html("Name already taken").slideDown("fast");
                        error = true;
                    }else
                        title_error.slideUp("fast");
                }
            });
        }
        
        if ($("#ctf_content").val() === ""){  // Thread must say something
            content_error.slideDown("fast");
            error = true;
        }else
            content_error.slideUp("fast");
        
        if (!error)
            $("#create_thread_form").submit();
    });
    
    
    
    // Toggles the color of a like button when hovering over one
    $(document).on("mouseenter mouseleave", ".like_button", function(){
        if (logged_in){
            var liked = "img/thumbs_up_green.png";
            var not_liked = "img/thumbs_up_grey.png";

            var img = $(this).children(0);

            var like_counter = $(this).next();
            var likes = parseInt(like_counter.html());

            if (img.attr("src") === liked){
                img.attr("src", not_liked);
                like_counter.html(likes - 1);
            }else if (img.attr("src") === not_liked){
                img.attr("src", liked);
                like_counter.html(likes + 1);
            }
        }
    });
    
    
    
    // Adds or removes a like on a thread for the logged in user
    $(document).on("click", ".like_button", function(){
        if (logged_in){
            var liked = "img/thumbs_up_green.png";
            var not_liked = "img/thumbs_up_grey.png";

            var img = $(this).children(0);

            var like_counter = $(this).next();
            var likes = parseInt(like_counter.html());

            // Changes the like button color and like count on the screen
            if (img.attr("src") === liked){
                img.attr("src", not_liked);
                like_counter.html(likes - 1);
            }else if (img.attr("src") === not_liked){
                img.attr("src", liked);
                like_counter.html(likes + 1);
            }
            
            var thread_id = $(this).parent().parent().parent().attr("data-thread_id");
            
            // Updates the database
            $.ajax({
                method: "post",
                url: "ajax/toggle_thread_like.php",
                data: {
                    "thread_id": thread_id
                }
            });
        }
    });
    
    
    
    // Search fields that the user types in
    $("#ts_username, #ts_title, #ts_tags").keyup(function(){
        refresh_threads();
    });
    
    
    
    // Search fields that the user selects
    $("#ts_date_from, #ts_date_to").change(function(){
        refresh_threads();
    });
    
    
    
    var timer;
    
    // Updates the threads on screen to match the search parameters
    function refresh_threads(){
        clearTimeout(timer);
        
        // Waits .8 seconds before refreshing
        // Allows the user to finish typing
        setTimeout(function(){
            $.ajax({
                method: "post",
                url: "ajax/get_threads.php",
                data: $("#thread_search_form").serialize(),
                success: function(data){
                    $("#threads").html(data);
                }
            });
        }, 800);
    }
});