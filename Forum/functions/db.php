<?php
    $dsn = "mysql:host=localhost;dbname=forum";
    $username = "root";
    $password = "";
    $db;
    
    try{
        $db = new PDO($dsn, $username, $password);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        error_reporting(E_ALL);
    }catch(PDOException $error_message){
        $error_message = $error_message->getMessage();
        exit();
    }