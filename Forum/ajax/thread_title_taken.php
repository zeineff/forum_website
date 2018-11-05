<?php
    // This file return checks if a thread exists with a given title

    require_once("../functions/functions.php");
    
    $fields = array("title");
    $taken = false;
    
    if (check_fields($fields)){
        $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);
        
        if (thread_title_taken($title)){
            $taken = true;
        }
    }
    
    // Returns true if the title is taken
    echo ($taken ? "True" : "False");