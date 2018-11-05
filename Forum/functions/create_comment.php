<?php
    require_once("session.php");
    require_once("functions.php");
    
    $fields = array("cc_thread_id", "cc_content");
    
    // If all required fields are set and user is logged on
    if (check_fields($fields) && $_SESSION["id"] !== -1){
        $thread_id = filter_input(INPUT_POST, "cc_thread_id", FILTER_SANITIZE_NUMBER_INT);
        $content = filter_input(INPUT_POST, "cc_content", FILTER_SANITIZE_STRING);
        
        create_comment($thread_id, $_SESSION["id"], $content);
    }
    
    header("Location:" . $_SESSION["last_page"]);