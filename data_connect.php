<?php 
    $dsn = 'mysql:host=localhost;dbname=mini_bbs;charset=utf8'; 
    $db_user = 'root'; 
    $db_pass = ''; 

    try { 
        $pdo = new PDO($dsn, $db_user, $db_pass); 
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    } catch (PDOException $e) { 
        print('Can not connect to Server:'.$e->getMessage()); 
        die(); 
    }
?>