<?php 
    session_start();
    
    include('data_connect.php');
    
    if (isset($_SESSION['login_name'])){
        header('Location: index.php');
        exit();
    }
    
    $error = '';
    
    if (isset($_POST['login'])){
        if (empty($_POST['email'])||empty($_POST['password'])){
            $message = '未入力項目があります';
        }else {
            $email = $_POST['email'];
            $pass = $_POST['password'];
            
            $data_check ='SELECT id, name, password, picture FROM members WHERE email = :email';
            $stmt_check = $pdo->prepare($data_check);
            $stmt_check->bindParam(':email', $email, PDO::PARAM_STR);            
            $stmt_check->execute();
            $row_check = $stmt_check->fetch(PDO::FETCH_ASSOC);
            
            if ($stmt_check->rowCount($row_check) === 1){
                if (password_verify($pass,$row_check['password'])){
	                $_SESSION['login_id'] = $row_check['id'];
	                $_SESSION['login_picture'] = $row_check['picture'];
	                $_SESSION['login_name']	= $row_check['name'];
	                header('Location:index.php');
	            }else{
	                $error = 'パスワードが誤りました';
	            }
            }else{
                $error = 'メールアドレスが存在しません';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <title>ログイン</title>
        <link rel="stylesheet" href="style1.css" />
    </head>
    <body>
        <h1>ログイン</h1>
        <div class="input_form">
            <p style="color:red;"><?php echo $error?></p>
            <form method="POST" action="">
                <input type="email" name="email" placeholder="メールアドレス">
                <input type="password" name="password" placeholder="パスワード">
                <button type="submit" name="login">ログイン</button>
            </form>
            <a href="register.php">会員登録はこちら</a>
        </div>
    </body>
</html>