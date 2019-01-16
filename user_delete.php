<?php 
    include('data_connect.php');
    
    session_start();
    
    if(!isset($_SESSION['login_id'])){
        header('Location: login.php');
        exit();
    } 
    $user_id = $_SESSION['login_id'];

    $data_del = 'DELETE FROM members WHERE id = :user_id';
    $stmt_del = $pdo->prepare($data_del);
    $stmt_del->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_del->execute();
    require('logout.php');
    header('Location: login.php');
?>