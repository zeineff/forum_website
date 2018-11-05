<!DOCTYPE html>

<?php
    require_once("functions/functions.php");
    
    $user_id = filter_input(INPUT_GET, "user_id", FILTER_SANITIZE_NUMBER_INT);
    
    if (!isset($user_id) || !user_id_taken($user_id)){
        header("Location:index.php");
    }
    
    $user = get_user_by_id($user_id);
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $user["username"] ?>'s profile</title>
        
        <link rel="stylesheet" type="text/css" href="css/main.css">
        
        <script src="js/jquery-3.3.1.js"></script>
        <script src="js/top.js"></script>
        <script src="js/profile.js"></script>
    </head>
    
    <body>
        <?php include("includes/top.php") ?>
        
        <main>
            <img alt="Profile avatar" id="profile_avatar" src="<?php echo "img/avatar/" . $user["avatar"] ?>">
            
            <span id="profile_username">
                <?php echo $user["username"] ?>
            </span>
            
            <?php if($user["id"] === $_SESSION["id"]) : ?>
                <form id="upload_avatar_form" method="post" action="functions/upload_avatar.php" enctype="multipart/form-data">
                    <input type="file" accept="image/*" id="avatar_upload" name="avatar_upload">

                    <input type="submit" id="upload_avatar_button" name="upload_avatar_button" value="Upload"><br/>
                    
                    <span id="upload_avatar_error"></span>
                </form>
            <?php endif ?>
        </main>
        
        <?php include("includes/footer.php") ?>
    </body>
</html>
