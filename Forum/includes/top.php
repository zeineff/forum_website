<?php
    require_once("functions/session.php");
    
    $_SESSION["last_page"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI'];
?>

<nav>
    <div id="top">
        <?php if ($_SESSION["id"] == -1) : ?>
            <form id="login_form" method="post" action="functions/login.php">
                <span id="login_error"></span>

                <div class="input">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Username">
                    <br/><p class="hidden_error">*Required</p>
                </div>

                <div class="input">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Password">
                    <br/><p class="hidden_error">*Required</p>
                </div>

                <input type="submit" id="login_button" value="Login">
                <input type="button" id="register_popup_button" value="Register">
            </form>
        <?php else : ?>
            <form id="logout_form" method="post" action="functions/logout.php">
                <input type="submit" id="logout_button" value="Logout">
            </form>

            <div id="user_display">
                <img alt="User avatar" src="img/avatar/<?php echo $_SESSION["avatar"] ?>">
                
                <span id="session_username">
                    <a href="<?php echo "profile.php?user_id=" . $_SESSION["id"] ?>">
                        <?php echo $_SESSION["username"] ?>
                    </a>
                </span>
            </div>
        <?php endif ?>

        <ul id="links">
            <li><a href="index.php">Home</a></li>
        </ul>

    </div>

    <div id="register_popup">
        <form id="register_form" method="post" action="functions/create_user.php">
            <canvas id="arrow" width="10" height="10">
                <script>
                    var arrow = document.getElementById("arrow");
                    var ctx = arrow.getContext("2d");
                    ctx.fillStyle = "#FFF";

                    ctx.moveTo(0,10);
                    ctx.lineTo(5,0);
                    ctx.lineTo(10,10);
                    ctx.closePath();
                    ctx.fill();

                    ctx.beginPath();
                    ctx.moveTo(0,10);
                    ctx.lineTo(5,0);
                    ctx.lineTo(10,10);
                    ctx.strokeStyle = "#CCC";
                    ctx.stroke();
                </script>
            </canvas>
            
            <div class="input">
                <label for="reg_username">Username:</label>
                <input type="text" id="reg_username" name="reg_username">
                <br/><span class="error"></span>
                <div style="clear:both"></div>
            </div>
            
            <div class="input">
                <label for="reg_password_01">Password:</label>
                <input type="password" id="reg_password_01" name="reg_password_01">
                <br/>
                <div style="clear:both"></div>
            </div>
            
            <div class="input">
                <label for="reg_password_02">Re-enter Password:</label>
                <input type="password" id="reg_password_02" name="reg_password_02">
                <br/><span class="error"></span>
                <div style="clear:both"></div>
            </div>
            
            <div class="input">
                <label for="reg_email">Email:</label>
                <input type="email" id="reg_email" name="reg_email">
                <br/><span class="error"></span>
                <div style="clear:both"></div>
            </div>
            
            <label for="register_button"></label>
            <input type="submit" id="register_button" name="register_button" value="Register">
        </form>
    </div>
</nav>