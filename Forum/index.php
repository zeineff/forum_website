<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Home</title>
        
        <link rel="stylesheet" type="text/css" href="css/main.css">
        
        <script src="js/jquery-3.3.1.js"></script>
        <script src="js/top.js"></script>
        <script src="js/index.js"></script>
    </head>
    
    <body>
        <?php include("includes/top.php") ?>
        
        <main>
            <form id="thread_search_form">
                <div id="thread_search_form_header">
                    <h3>Search threads</h3>
                </div>
                
                <label for="ts_title">Title:</label>
                <input type="text" id="ts_title" name="ts_title"><br/>
                
                <label for="ts_username">Poster:</label>
                <input type="text" id="ts_username" name="ts_username"><br/>
                
                <label for="ts_date_from">Date from:</label>
                <input type="date" id="ts_date_from" name="ts_date_from"><br/>
                
                <label for="ts_date_to">Date to:</label>
                <input type="date" id="ts_date_to" name="ts_date_to"><br/>
                
                <label for="ts_tags">Tags:</label>
                <input type="text" id="ts_tags" name="ts_tags"><br/>
            </form>
            
            <div id="threads">
                <?php include("includes/threads.php") ?>
            </div>
            
            <hr/><br/>
            
            <div id="create_thread">
                <?php if ($_SESSION["id"] !== -1) : ?>
                    <form id="create_thread_form" method="post" action="functions/create_thread.php">
                        <h3>Create new thread</h3><br/>

                        <div class="input">
                            <label for="ctf_title">Thread title:</label><br/>
                            <input type="text" id="ctf_title" name="ctf_title">
                            <span id="ctf_title_error" class="error"></span>
                        </div>

                        <div class="input">
                            <label for="ctf_comments">Allow comments:</label><br/>
                            <select id="ctf_comments" name="ctf_comments">
                                <option value="True">Yes</option>
                                <option value="False">No</option>
                            </select>
                        </div><br/><br/>

                        <label for="ctf_content">Content: </label><br/>
                        <textarea id="ctf_content" name="ctf_content" cols="50" rows="5"></textarea><br/>
                        <span id="ctf_content_error" class="error">*Required</span><br/><br/>

                        <label for="ctf_tags">Tags (Comma separated):</label>
                        <input type="text" id="ctf_tags" name="ctf_tags"><br/><br/>

                        <input type="submit" id="create_thread_button" name="create_thread_button" value="Create"><br/>
                    </form>
                <?php else : ?>
                    <span id="ctf_not_logged_in">Log in to create a thread</span>
                <?php endif ?>
            </div><br/><hr/>
        </main>
        
        <?php
            require_once("functions/functions.php");
            get_thread_by_title("qwerasd");
        ?>
        
        <?php include("includes/footer.php") ?>
    </body>
</html>
