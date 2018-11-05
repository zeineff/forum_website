<?php
    // This file creates any error messages for a login
        
    require_once("../functions/functions.php");
    
    $fields = array("username", "password");
    
    // If not all required fields are set
    if (!check_fields($fields)){
        echo "Login details missing";
    }else{
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
        
        $user = get_user_by_username($username);
        
        // If the user doesn't exist or the passwords don't match
        if ($user === null || !password_verify($password, $user["password"])){
            echo "Incorrect login details";
        }else{
            echo "Valid login details";
        }
    }