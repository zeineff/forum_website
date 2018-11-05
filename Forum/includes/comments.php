<?php
    // If ajax call
    if (!isset($thread_id)){
        require_once("../functions/functions.php");
        $thread_id = filter_input(INPUT_POST, "thread_id", FILTER_SANITIZE_NUMBER_INT);
    }else{
        require_once("functions/functions.php");
    }
    
    $comments = get_comments_by_thread_id($thread_id);
?>

<?php foreach ($comments as $c) : ?>
    <div class="comment">
        <div class="poster">
            <span class="post_creator">
                <a href="<?php echo "profile.php?user_id=" . $c["user_id"] ?>">
                    <?php echo $c["username"] ?>
                </a>
            </span>
            <img alt="Post creater avatar" class="post_avatar" src="img/avatar/<?php echo $c["avatar"] ?>">
        </div>
        
        <div class="post_content">
            <div class="date"><?php echo format_date($c["date_created"]) ?></div>
            
            <div class="post_text">
                <p>
                    <?php
                        // Words to not display in comments
                        $censored_words = ["fuck", "fucks", "fucking", "fucked", "shit"];
                        $text = $c["content"];
                        
                        // Replacement for censored workd
                        $replacement = "****";
                        
                        // Replaces all instances of censored words
                        $censored = preg_replace('/\b('. implode('|',$censored_words) .')\b/i', $replacement, $text);
                        
                        echo $censored;
                    ?>
                </p>
            </div><br/>
        </div>
    </div>
<?php endforeach;