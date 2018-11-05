<?php
    require_once("session.php");
    require_once("functions.php");
    
    $fields = array("ctf_title", "ctf_comments", "ctf_content");
    
    // If all required fields are set and user is logged on
    if (check_fields($fields) && $_SESSION["id"] !== -1){
        $title = filter_input(INPUT_POST, "ctf_title", FILTER_SANITIZE_STRING);
        $comments = filter_input(INPUT_POST, "ctf_comments", FILTER_SANITIZE_STRING);
        $content = filter_input(INPUT_POST, "ctf_content", FILTER_SANITIZE_STRING);
        $tags_string = filter_input(INPUT_POST, "ctf_tags", FILTER_SANITIZE_STRING);
        
        // Thread titles must be unique
        if (!thread_title_taken($title)){
            $comments_enabled = ($comments === "True" ? 1 : 0);
            create_thread($_SESSION["id"], $title, $comments_enabled);
        
            $thread = get_thread_by_title($title);
            create_comment($thread["thread_id"], $_SESSION["id"], $content);
            
            // Creates any new tags needed and links them to the thread
            if (!empty($tags_string)){
                // Comma seperates tags
                $tags = explode(",", $tags_string);
                
                foreach ($tags as $tag_name){
                    // Removes any whitespace
                    $tag_name = trim($tag_name);
                    
                    // Checks if tag is entirely whitespace
                    if (strlen(trim($tag_name)) !== 0){
                        $tag = get_tag_by_name($tag_name);
                        
                        // Checks if tag already exists
                        if ($tag === null){
                            $tag = create_tag($tag_name);
                        }

                        // Avoids tag being added to thread more than once
                        if (!thread_has_tag($thread["thread_id"], $tag["id"])){
                            create_thread_tag($thread["thread_id"], $tag["id"]);
                        }
                    }
                }
            }
        }
    }
    
    header("Location:" . $_SESSION["last_page"]);