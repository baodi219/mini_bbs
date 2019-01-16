<?php
    include('data_connect.php');
    
    session_start();
    
    if (isset($_POST['mes_post'])){
        if (empty($_POST['message'])){
            echo '投稿内容が未入力です';
            header('Location: index.php');
        }else {
            $user_id = $_SESSION['login_id'];
            $message = $_POST['message'];
            try {
                $stmt = $pdo->prepare('INSERT INTO posts(message, member_id, reply_post_id, created) VALUES (:message, :member_id, :reply_post_id, :date)');
                $date = new Datetime();
                $date = $date->format('Y-m-d H:i:s');
                $stmt->bindParam(':message', $message, PDO::PARAM_STR);
                $stmt->bindValue(':member_id', $user_id, PDO::PARAM_INT);
                $stmt->bindValue(':reply_post_id', 0, PDO::PARAM_INT);
                $stmt->bindValue(':date', $date, PDO::PARAM_STR);
                $stmt->execute();
                header('Location: index.php');
            } catch (Exception $e) {
                $e->getMessage();
            }
        }
    }
?>