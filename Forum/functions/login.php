<?php
    require_once("session.php");
    require_once("functions.php");
    
    $fields = array("username", "password");
    
    // If all required fields are set
    if (check_fields($fields)){
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
        
        $user = get_user_by_username($username);
        
        // If the user exists and the given password matches
        if ($user !== null && password_verify($password, $user["password"])){
            set_session($user);
        }
    }
    
    header("Location:" . $_SESSION["last_page"]);