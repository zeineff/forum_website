<?php
    require_once("session.php");
    require_once("functions.php");
    
    $fields = array("reg_username", "reg_password_01", "reg_password_02", "reg_email");
    
    // If all required fields are set
    if (check_fields($fields)){
        $username = filter_input(INPUT_POST, "reg_username", FILTER_SANITIZE_STRING);
        $password_01 = filter_input(INPUT_POST, "reg_password_01", FILTER_SANITIZE_STRING);
        $password_02 = filter_input(INPUT_POST, "reg_password_02", FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, "reg_email", FILTER_SANITIZE_EMAIL);
        
        // If password match and the username and email are not taken
        if ($password_01 === $password_02 && !username_taken($username) && !email_taken($email)){
            $user = create_user($username, $password_01, $email);
            
            set_session($user);
        }
    }
    
    header("Location:" . $_SESSION["last_page"]);