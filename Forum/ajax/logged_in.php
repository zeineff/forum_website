<?php
    // This file returns 'True' if the user is logged in and 'False' if not
    
    require_once("../functions/session.php");
    
    echo ($_SESSION["id"] === -1 ? "False" : "True");