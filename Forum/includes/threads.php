<?php
    $ajax_call = filter_input(INPUT_POST, "ajax", FILTER_SANITIZE_STRING);
    
    if (isset($ajax_call)){
        require_once("../functions/functions.php");
    }else{
        require_once("functions/functions.php");
    }

    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);
    $date_from = filter_input(INPUT_POST, "date_from", FILTER_SANITIZE_STRING);
    $date_to = filter_input(INPUT_POST, "date_to", FILTER_SANITIZE_STRING);
    $tags = filter_input(INPUT_POST, "tags", FILTER_SANITIZE_STRING);
    
    if (!isset($username)){
        $username = "";
    }
    
    if (!isset($title)){
        $title = "";
    }
    
    if (!isset($date_from)){
        $date_from = "0000-01-01 00:00:00";
    }
    
    if (!isset($date_to)){
        $date_to = "9999-12-31 23:59:59";
    }
    
    if (!isset($tags)){
        $tags = "";
    }
    
    $tags_split = split_tags($tags);
    
    echo $username;
    
    $threads = search($username, $title, $date_from, $date_to, $tags_split);
?>


<?php foreach ($threads as $t) : ?>
    <div class="thread" data-thread_id="<?php echo $t["thread_id"] ?>">
        <div class="poster">
            <span class="post_creator">
                <a href="<?php echo "profile.php?user_id=" . $t["user_id"] ?>">
                    <?php echo $t["username"] ?>
                </a>
            </span>
            
            <img alt="Post creater avatar" class="post_avatar" src="img/avatar/<?php echo $t["avatar"] ?>">
        </div>

        <div class="post_content">
            <div class="date"><?php echo format_date($t["date_created"]) ?></div>

            <div class="post_text">
                <a href="thread.php?thread_id=<?php echo $t["thread_id"] ?>"><?php echo $t["title"] ?></a>
            </div><br/>

            <ul class="tags">
                <?php
                    $tags = get_thread_tags("thread_id", $t["thread_id"]);

                    foreach($tags as $tag) : ?>
                        <li><?php echo $tag["tag"] ?></li>
                    <?php endforeach;
                ?>
            </ul>

            <div class="likes">
                <button class="like_button">
                    <img alt="Like button" src="<?php
                        if (thread_liked_by($t["thread_id"], $_SESSION["id"])){
                            echo "img/thumbs_up_green.png";
                        }else{
                            echo "img/thumbs_up_grey.png";
                        }
                    ?>">
                </button>

                <span class="like_count"><?php echo $t["likes"] ?></span>
            </div>
        </div>
    </div>
<?php endforeach ?>