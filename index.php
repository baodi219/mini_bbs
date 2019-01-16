<?php
    include('data_connect.php');
    
    session_start();
    
    if (!isset($_SESSION['login_name'])){
        header('Location: login.php');
        exit();
    }
    
    $sql_post= "SELECT posts.id, posts.message, posts.modified, members.name, members.picture FROM posts, members WHERE posts.member_id = members.id AND posts.reply_post_id = 0 ORDER BY posts.modified DESC";
    $stml_post= $pdo->prepare($sql_post);
    $stml_post->execute();
    
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <title>掲示板</title>
        <link rel="stylesheet" href="style1.css" />
    </head>
    
    <body>
        <h1>中央学校掲示板</h1>
        <div class="infomation">
            <img src="<?php echo $_SESSION['login_picture'] ?>" width="50px" height="50px" />
            <b><?php echo $_SESSION['login_name'] ?><?php $_SESSION['login_id'] ?></b>
            <a href="logout.php">ログアウト</a>
            <a href="user_update.php"> 情報変更</a>
            <a href="user_delete.php" style="color:red; font-weight:bold;">退会</a>
        </div>
        <div class="bbs_form">
            <p>メッセージ</p>
            <form action="bbs_post.php" method="POST">
                <textarea name="message" rows="3"></textarea>
                <button type="submit" name="mes_post">送信</button>
            </form>
        </div>
        <?php while($row_post = $stml_post->fetch(PDO::FETCH_ASSOC)){ ?>
        <div class="message">
            <img src="<?php echo $row_post['picture'] ?>" width="30px" height="30px" />
            <b>@<?php echo $row_post['name'] ?>さん</b>
            <?php if($row_post['name'] === $_SESSION['login_name']){ ?><a href="bbs_delete.php?del=<?php echo $row_post['id']; ?>">削除</a><?php } ?>
            <p class="poster"><?php echo $row_post['message'] ?></p>
            <p class="time">Posted at: <?php echo $row_post['modified'] ?></p>
            <div class="message_reply">
                <?php   $sql_reply= "SELECT posts.id, posts.message, posts.modified, members.name, members.picture FROM posts, members WHERE posts.member_id = members.id AND posts.reply_post_id = :id ORDER BY posts.created ASC";
                        $stml_reply= $pdo->prepare($sql_reply);
                        $stml_reply->bindParam(':id', $row_post['id'], PDO::PARAM_STR);
                        $stml_reply->execute();
                        while ($row_reply = $stml_reply->fetch(PDO::FETCH_ASSOC)){ ?>
                <div class="message_reply_box">        
                    <img src="<?php echo $row_reply['picture'] ?>" width="30px" height="30px" />
                    <b>@<?php echo $row_reply['name'] ?>さん</b>
                    <?php if($row_reply['name'] === $_SESSION['login_name']){ ?><a href="bbs_delete.php?del=<?php echo $row_reply['id']; ?>">削除</a><?php } ?>
                    <p class="poster"><?php echo $row_reply['message'] ?></p>
                    <p class="time">Posted at: <?php echo $row_reply['modified'] ?></p>
                </div>
                <?php } ?>
                <div class="bbs_form">
                    <p>返信内容</p>
                    <form action="bbs_post_reply.php" method="POST">
                        <textarea name="message" rows="2"></textarea>
                        <button type="submit" name="post_reply" value="<?php echo $row_post['id'] ?>">返信</button>
                    </form>
                </div>
            </div>
        </div>
        <?php } ?>
    </body>
</html>