<?php
    require_once("db.php");

    // Returns true if each field in '$fields' has a value ready in POST
    function check_fields($fields){
        foreach ($fields as $field){
            $a = filter_input(INPUT_POST, $field);
            
            if ($a === null || !isset($a) || empty($a)){
                return false;
            }
        }
        
        return true;
    }
    
    // Formats a date to fit the format stored in '$date_format'
    function format_date($s){
        $date_format = "dS F Y, H:i:s";
        return date_format(date_create($s), $date_format);
    }
    
    // Sets the current session variables to be those of $user
    function set_session($user){
        $_SESSION["id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["avatar"] = $user["avatar"];
        $_SESSION["password"] = $user["password"];
    }
    
    function update_session(){
        $user = get_user_by_id($_SESSION["id"]);
        set_session($user);
    }
    
    function split_tags($tags_string){
        $tags = [];
        
        if (!empty($tags_string)){
            $tags = explode(",", $tags_string);
            
            for ($i = 0; $i < sizeof($tags); $i++){
                $tags[$i] = trim($tags[$i]);
            }
        }
        
        return $tags;
    }
    
    
    
    
    
    
    
    
    
    
    // Customizable select query. Compares using LIKE
    function search_table_like($table, $field, $value, $order = "1", $direction = "DESC"){
        global $db;
        
        $string = "SELECT * FROM " . $table . " WHERE " . $field . " LIKE '%" . $value . "%' ORDER BY " . $order . " " . $direction;
        $query = $db -> prepare($string);
        $query -> execute();
        $threads = $query -> fetchAll();
        $query -> closeCursor();
        
        return $threads;
    }
    
    // Customizable select query. Compares using =
    function search_table_equal($table, $field, $value, $order = "1", $direction = "DESC"){
        global $db;
        
        $string = "SELECT * FROM " . $table . " WHERE " . $field . " = :value ORDER BY " . $order . " " . $direction;
        $query = $db -> prepare($string);
        $query -> bindValue(":value", $value);
        $query -> execute();
        $threads = $query -> fetchAll();
        $query -> closeCursor();
        
        return $threads;
    }
    
    // Customizable update query
    function update_table_equal($table, $field, $value, $where_field, $where_value){
        global $db;
        
        $string = "UPDATE " . $table . " SET " . $field . " = :value WHERE " . $where_field . " = :where_field";
        $query = $db -> prepare($string);
        $query -> bindValue(":value", $value);
        $query -> bindValue(":where_field", $where_value);
        $query -> execute();
        $query -> closeCursor();
    }
    
    // Returns a single row from a table
    function get_unique_field($table, $field, $value){
       $results = search_table_equal($table, $field, $value);
       
        if (sizeof($results) === 1){
            return $results[0];
        }else{
            return null;
        }
    }
    
    
    
    
    
    
    
    
    
    
    // Function names are self explanatory
    // All functions below call the above ones to obtain their data
    
    function search_threads($field, $value, $order, $direction){
        return search_table_like("view_threads", $field, $value, $order, $direction);
    }
    
    function get_thread_tags($field, $value){
        return search_table_equal("view_tags", $field, $value);
    }
    
    function get_threads($field, $value, $order = "1", $direction = "DESC"){
        return search_table_equal("view_threads", $field, $value, $order, $direction);
    }
    
    function get_thread_by_unique_field($field, $value){
        return get_unique_field("view_threads", $field, $value);
    }
    
    function get_thread_by_id($id){
        return get_thread_by_unique_field("thread_id", $id);
    }
    
    function get_thread_by_title($title){
        return get_thread_by_unique_field("title", $title);
    }
    
    function thread_field_taken($field, $value){
        return get_thread_by_unique_field($field, $value) !== null;
    }
    
    function thread_title_taken($title){
        return thread_field_taken("title", $title);
    }
    
    function thread_id_taken($id){
        return thread_field_taken("thread_id", $id);
    }
    
    function create_thread($user_id, $title, $comments){
        global $db;
        
        $string = "INSERT INTO threads (user_id, title, comments) VALUES (:user_id, :title, :comments)";
        $query = $db -> prepare($string);
        $query -> bindValue(":user_id", $user_id);
        $query -> bindValue(":title", $title);
        $query -> bindValue(":comments", $comments);
        $query -> execute();
        $query -> closeCursor();
    }
    
    
    
    function get_users($field, $value){
        return search_table_equal("users", $field, $value);
    }
    
    function get_user_by_unique_field($field, $value){
        return get_unique_field("users", $field, $value);
    }
    
    function get_user_by_id($id){
        return get_user_by_unique_field("id", $id);
    }
    
    function get_user_by_username($username){
        return get_user_by_unique_field("username", $username);
    }
    
    function get_user_by_email($email){
        return get_user_by_unique_field("email", $email);
    }
    
    function get_user_by_avatar($avatar){
        return get_user_by_unique_field("avatar", $avatar);
    }
    
    function set_user_avatar($user_id, $avatar){
        update_table_equal("users", "avatar", $avatar, "id", $user_id);
    }
    
    function user_field_taken($field, $value){
        return get_user_by_unique_field($field, $value) !== null;
    }
    
    function user_id_taken($id){
        return user_field_taken("id", $id);
    }
    
    function username_taken($username){
        return user_field_taken("username", $username);
    }
    
    function avatar_taken($avatar){
        return user_field_taken("avatar", $avatar);
    }
    
    function email_taken($email){
        return user_field_taken("email", $email);
    }
    
    function create_user($username, $password, $email){
        global $db;
        
        $string = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $query = $db -> prepare($string);
        $query -> bindValue(":username", $username);
        $query -> bindValue(":password", password_hash($password, PASSWORD_DEFAULT));
        $query -> bindValue(":email", $email);
        $query -> execute();
        $query -> closeCursor();
        
        return get_user_by_username($username);
    }
    
    
    
    function get_comments($field, $value){
        return search_table_equal("view_comments", $field, $value);
    }
    
    function get_comments_by_thread_id($thread_id, $order = "date_created", $direction = "ASC"){
        return search_table_equal("view_comments", "thread_id", $thread_id, $order, $direction);
    }
    
    function create_comment($thread_id, $user_id, $content){
        global $db;
        
        $string = "INSERT INTO comments (thread_id, user_id, content) VALUES (:thread_id, :user_id, :content)";
        $query = $db -> prepare($string);
        $query -> bindValue(":thread_id", $thread_id);
        $query -> bindValue(":user_id", $user_id);
        $query -> bindValue(":content", $content);
        $query -> execute();
        $query -> closeCursor();
    }
    
    
    
    function get_tags($field, $value){
        return search_table_equal("tags", $field, $value);
    }
    
    function get_tag_by_unique_field($field, $value){
        return get_unique_field("tags", $field, $value);
    }
    
    function get_tag_by_name($tag){
        return get_unique_field("tags", tag, $tag);
    }
    
    function get_tag_id($tag){
        return get_unique_field("tags", "tag", $tag)["id"];
    }
    
    function create_tag($tag){
        global $db;
        
        $string = "INSERT INTO tags (tag) VALUES (:tag)";
        $query = $db -> prepare($string);
        $query -> bindValue(":tag", $tag);
        $query -> execute();
        $query -> closeCursor();
        
        return get_tag_by_name($tag);
    }
    
    
    
    function thread_has_tag($thread_id, $tag_id){
        $tags = search_table_equal("thread_tags", "thread_id", $thread_id);
        
        foreach ($tags as $tag){
            if ($tag["tag_id"] === $tag_id){
                return true;
            }
        }
        
        return false;
    }
    
    function create_thread_tag($thread_id, $tag_id){
        global $db;
        
        $string = "INSERT INTO thread_tags (thread_id, tag_id) VALUES (:thread_id, :tag_id)";
        $query = $db -> prepare($string);
        $query -> bindValue(":thread_id", $thread_id);
        $query -> bindValue(":tag_id", $tag_id);
        $query -> execute();
        $query -> closeCursor();
    }
    
    
    
    function thread_liked_by($thread_id, $user_id){
        $thread_likes = search_table_equal("thread_likes", "thread_id", $thread_id);
        
        foreach ($thread_likes as $a) {
            if ($a["user_id"] === $user_id){
                return true;
            }
        }
        
        return false;
    }
    
    function like_thread($thread_id, $user_id){
        global $db;
        
        $string = "INSERT INTO thread_likes (thread_id, user_id) VALUES (:thread_id, :user_id)";
        $query = $db -> prepare($string);
        $query -> bindValue(":thread_id", $thread_id);
        $query -> bindValue(":user_id", $user_id);
        $query -> execute();
        $query -> closeCursor();
    }
    
    function unlike_thread($thread_id, $user_id){
        global $db;
        
        $string = "DELETE FROM thread_likes WHERE thread_id = :thread_id AND user_id = :user_id";
        $query = $db -> prepare($string);
        $query -> bindValue(":thread_id", $thread_id);
        $query -> bindValue(":user_id", $user_id);
        $query -> execute();
        $query -> closeCursor();
    }
    
    function toggle_thread_like($thread_id, $user_id){
        if (thread_liked_by($thread_id, $user_id)){
            unlike_thread($thread_id, $user_id);
        }else{
            like_thread($thread_id, $user_id);
        }
    }
    
    
    
    // Returns all the threads with fields similar to the inputs here
    // Needs to build up the query to account for all tags being searched
    function search($username, $title, $date_from, $date_to, $tags){
        global $db;
        
        $string = "SELECT * FROM view_threads_with_tags WHERE username LIKE '%" . $username . "%' AND title LIKE '%" . $title . "%' AND date_created > '" . $date_from . "' AND date_created < '" . $date_to . "'";
        
        // Adds on a section to include threads having one of the inputted tags
        if (sizeof($tags) !== 0){
            $string = $string . " AND (tag LIKE '%" . $tags[0] . "%'";
            
            for ($i = 1; $i < sizeof($tags); $i++){
                $string = $string . " OR tag LIKE '%" . $tags[$i] . "%'";
            }
            
            $string = $string . ")";
        }
        
        // Removes duplicate threads caused by them having multiple tags
        // Orders by newest to oldest
        $string = $string . " GROUP BY thread_id ORDER BY date_created DESC";
        
        $query = $db -> prepare($string);
        $query -> bindValue(":date_from", $date_from);
        $query -> bindValue(":date_to", $date_to);
        $query -> execute();
        $threads = $query -> fetchAll();
        $query -> closeCursor();
        
        return $threads;
    }