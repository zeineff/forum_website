<?php
    // This file is used to check if a username or email is already taken.

    require_once("../functions/functions.php");
    
    $fields = array("field", "value");
    $taken = false;
    
    // If all required fields are set
    if (check_fields($fields)){
        $field = filter_input(INPUT_POST, "field", FILTER_SANITIZE_STRING);
        $value = filter_input(INPUT_POST, "value", FILTER_SANITIZE_STRING);
        
        if (user_field_taken($field, $value)){
            $taken = true;
        }
    }
    
    // Returns true if the username or email is taken
    echo ($taken ? "True" : "False");