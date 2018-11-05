<?php
    // This file toggles whether a user has liked a thread or not

    require_once("../functions/session.php");
    require_once("../functions/functions.php");
    
    $fields = array("thread_id");
    $success = false;
    
    // If all required fields are set and the user is logged in
    if (check_fields($fields) && $_SESSION["id"] !== -1){
        $thread_id = filter_input(INPUT_POST, "thread_id", FILTER_SANITIZE_STRING);
        
        // If a thread with the given id exists
        if (thread_id_taken($thread_id)){
            toggle_thread_like($thread_id, $_SESSION["id"]);
            
            $success = true;
        }
    }
    
    // Returns 'True' if there were no errors
    echo ($success ? "True" : "False");