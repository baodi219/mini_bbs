<?php
    include('data_connect.php');
    
    session_start();
    
    if (isset($_SESSION['login_name'])){
        header('Location: index.php');
        exit();
    }
    
    $error = '';
    $success = '';
    
    if (isset($_POST['register'])){
        if (empty($_POST['nickname'])||empty($_POST['email'])||empty($_POST['password'])){
            $error = '未入力項目があります';
        }else {
            $nickname = $_POST['nickname'];
            $email = $_POST['email'];
            $pass = $_POST['password'];
            
            $data_check ='SELECT name, email, password FROM members WHERE name = :nickname OR email = :email';
            $stmt_check = $pdo->prepare($data_check);
            $stmt_check->bindParam(':nickname', $nickname, PDO::PARAM_STR);
            $stmt_check->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt_check->execute();
            $row_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($stmt_check->rowCount($row_check) >= 1){
	            $error = 'ニックネームまたはメールアドレスが既に使用しています';
            }elseif (strlen($pass) < 4) {
                $error = 'パスワードは4文字以上ご入力ください';
            }else{
                $success = '登録が成功しました。<br/> ログインができました';
                
                // ####### IMAGE UPLOAD #######
                if (isset($_FILES['image']['name'])){
                    $img_name = $_FILES['image']['name'];
                    $img_newname = time().$img_name;
                    $tmp_name = $_FILES['image']['tmp_name'];
                    $newpath = './uploaded_img/'.$img_newname;
                    move_uploaded_file($tmp_name, $newpath);
                }
                try {
                    $data_insert = 'INSERT INTO members(name, email, password, picture, created) VALUES (:nickname, :email, :password, :image, :date)';
                    $stmt_insert = $pdo->prepare($data_insert);
                    $date = new Datetime();
                    $date = $date->format('Y-m-d H:i:s');
                    $newpass = password_hash($pass, PASSWORD_DEFAULT);
                    $stmt_insert->bindParam(':nickname', $nickname, PDO::PARAM_STR);
                    $stmt_insert->bindParam(':email', $email, PDO::PARAM_STR);
                    $stmt_insert->bindParam(':password', $newpass, PDO::PARAM_STR);
                    $stmt_insert->bindParam(':image', $newpath, PDO::PARAM_STR);
                    $stmt_insert->bindValue(':date', $date, PDO::PARAM_STR);
                    $stmt_insert->execute();
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
        <title>会員登録</title>
        <link rel="stylesheet" href="style1.css" />
    </head>
    <body>
        <h1>会員登録</h1>
        <div class="input_form">
            <p><?php
                if (!empty($success)){
                    echo '<span style="color:green;">'.$success.'</span>';
                }else{
                    echo '<span style="color:red;">'.$error.'</span>';
                }
            ?></p>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="text" name="nickname" placeholder="ニックネーム">
                <input type="email" name="email" placeholder="メールアドレス">
                <input type="password" name="password" placeholder="パスワード">
                <h5>プロフィール写真をアップロード</h5>
                <input type="file" name="image">
                <button type="submit" name="register">確認</button>
            </form>
            <a href="login.php">ログインはこちら</a>    
        </div>
    </body>
</html>
