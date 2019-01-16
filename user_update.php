<?php 
    include('data_connect.php');
    
    session_start();
    
    if (!isset($_SESSION['login_id'])){
        header('Location: login.php');
        exit();
    }
    
    $error = '';
    if (isset($_POST['update'])){
        if (empty($_POST['password'])){
            $error = 'パスワードをご入力ください';
        }else {
            $user_id = $_SESSION['login_id'];
            $pass = $_POST['password'];
            
            if (strlen($pass) < 4){
                $error = 'パスワードは4文字以上ご入力ください';
            }else{
                
                // ####### IMAGE UPLOAD #######
                if (isset($_FILES['image']['name'])){
                    $img_name = $_FILES['image']['name'];
                    $img_newname = time().$img_name;
                    $tmp_name = $_FILES['image']['tmp_name'];
                    $newpath = './uploaded_img/'.$img_newname;
                    move_uploaded_file($tmp_name, $newpath);
                }
                // #### USER'S INFOMATION UPDATE ####
                try {
                    $data_reup = 'UPDATE members SET password = :password, picture = :image WHERE id = :id ';
                    $stmt_reup = $pdo->prepare($data_reup);
                    $newpass = password_hash($pass, PASSWORD_DEFAULT);
                    $stmt_reup->bindParam(':password', $newpass, PDO::PARAM_STR);
                    $stmt_reup->bindParam(':image', $newpath, PDO::PARAM_STR);
                    $stmt_reup->bindValue(':id', $user_id, PDO::PARAM_INT);
                    $stmt_reup->execute();
                    require('logout.php');
                    header('Location: login.php');
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <title>会員情報変更</title>
        <link rel="stylesheet" href="style1.css" />
    </head>
    <body>
        <h1>会員情報変更</h1>
        <div class="input_form">
            <p><?php echo '<span style="color:red;">'.$error.'</span>'; ?></p>
            <form method="POST" enctype="multipart/form-data" action="">
                <h5>新しいパスワード</h5>
                <input type="password" name="password">
                <h5>新しい写真をアップロード</h5>
                <input type="file" name="image">
                <button type="submit" name="update">変更</button>
            </form>
            <a href="index.php">ホームページ</a>
        </div>
    </body>
</html>