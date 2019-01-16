<?php 
    include('data_connect.php');
    
    if (isset($_GET['del'])){
        $id = $_GET['del'];

        $sql = 'DELETE FROM posts WHERE id = :delete_id OR reply_post_id = :id' ;
        $stmt = $pdo -> prepare($sql);
        $stmt -> bindParam(':delete_id', $id, PDO::PARAM_INT);
        $stmt -> bindParam(':id', $id, PDO::PARAM_INT);        
        $stmt -> execute();
    }
    header('Location: index.php');
?>