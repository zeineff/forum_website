<?php
    // This file is used to upload an avatar for a logged in user.
    // Appropriate errors are returned if they are encountered.
            
    require_once("../functions/session.php");
    require_once("../functions/functions.php");
    
    // If an image is available
    if (isset($_FILES["avatar_upload"])){
        
        // Image names are stored as random numbers between these two values
        $img_name_min = 100000000;
        $img_name_max = 999999999;
        
        $img = $_FILES["avatar_upload"];
        $img_name = basename($img["name"]);
        $format = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
        $file = "../img/avatar/default.png";  // Directory of new image
        
        // Recreates name for new file if current one already taken
        // Initial name of 'default.png' is taken
        while (file_exists($file)){
            $new_name = rand($img_name_min, $img_name_max) . "." . $format;
            $file = "../img/avatar/" . $new_name;
        }
        
        $max_size = 500000;  // Max image size in bytes
        $formats = ["png"];  // Array of accepted image formats
        $max_dimensions = [256, 256];  // Max height and width for image
        
        if ($img["size"] <= $max_size){  // If file size valid
            if (in_array($format, $formats)){  // If format valid
                $d = getimagesize($img["tmp_name"]);
                $h = $d[0];
                $w = $d[1];
                
                // If height and width valid
                if ($h <= $max_dimensions[0] && $w <= $max_dimensions[1]){
                    if (move_uploaded_file($img["tmp_name"], $file)){
                        set_user_avatar($_SESSION["id"], $new_name);
                        update_session();
                        
                        echo "Avatar upload successful";
                    }else{
                        echo "Avatar upload failed";
                    }
                }else{
                    echo "Image must be less than 256x256 pixels";
                }
            }else{
                echo "Image format not supported";
            }
        }else{
            echo "Image size too big";
        }
    }else{
        echo "Image not found";
    }