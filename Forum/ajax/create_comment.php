<?php
    // This file creates a comment on a thread
    
    require_once("../functions/session.php");
    require_once("../functions/functions.php");
    
    $fields = array("cc_thread_id", "cc_content");
    
    // If all required fields are set and the user is logged in
    if (check_fields($fields) && $_SESSION["id"] !== -1){
        $thread_id = filter_input(INPUT_POST, "cc_thread_id", FILTER_SANITIZE_NUMBER_INT);
        $content = filter_input(INPUT_POST, "cc_content", FILTER_SANITIZE_STRING);
        
        create_comment($thread_id, $_SESSION["id"], $content);
        
        echo "Comment successfully created";
    }else{
        echo "Fields missing";
    }