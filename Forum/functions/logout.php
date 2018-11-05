<?php
    require_once("session.php");
    session_destroy();
    
    header("Location:" . $_SESSION["last_page"]);