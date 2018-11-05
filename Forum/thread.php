<!DOCTYPE html>

<?php
    require_once("functions/functions.php");
    
    $thread_id = filter_input(INPUT_GET, "thread_id", FILTER_SANITIZE_NUMBER_INT);
    $thread = get_thread_by_id($thread_id);
    
    if (!isset($thread_id) || $thread === null){
        header("Location:index.php");
    }
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $thread["title"] ?></title>
        
        <link rel="stylesheet" type="text/css" href="css/main.css">
        
        <script src="js/jquery-3.3.1.js"></script>
        <script src="js/top.js"></script>
        <script src="js/thread.js"></script>
    </head>
    
    <body>
        <?php include("includes/top.php") ?>
        
        <main>
            <div id="thread_header">
                <img alt="Thread creater avatar" id="thread_avatar" src="img/avatar/<?php echo $thread["avatar"] ?>">
        
                <div id="thread_header_text">
                    <h1 id="thread_title"><?php echo $thread["title"] ?></h1>
                    <div id="thread_details">
                        <?php
                            echo "Create by " . $thread["username"] . ", ";
                            echo format_date($thread["date_created"]);
                        ?>
                    </div>
                </div>
            </div>
            
            <div id="comments">
                <?php include("includes/comments.php") ?>
            </div>
            
            <hr/><br/>
            
            <?php if ($thread["comments"] === 1) : ?>
                <div id="create_comment">
                    <?php if ($_SESSION["id"] !== -1) : ?>
                        <form id="create_comment_form" method="post" action="functions/create_comment.php">
                            <h3>Create new comment</h3><br/>

                            <input type="hidden" id="cc_thread_id" name="cc_thread_id" value="<?php echo $thread_id ?>">

                            <textarea id="cc_content" name="cc_content" cols="50" rows="5"></textarea>
                            <span id="cc_content_error" class="error">*Required</span><br/>

                            <input type="submit" id="create_comment_button" name="create_comment_button" value="Add"><br/>
                        </form>
                    <?php else : ?>
                        <span id="cc_not_logged_in">Log in to add a comment</span>
                    <?php endif ?>
                </div><br/><hr/>
            <?php else : ?>
                <span id="no_comments">Comments are disabled for this thread</span>
            <?php endif ?>
            
            <br/><br/><hr/>
        </main>
        
        <?php include("includes/footer.php") ?>
    </body>
</html>
