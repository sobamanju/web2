<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset ="utf-8">
</head>
<body>
    <?php
    if($_POST['sinki']){
        header('Location:./kaiin.php');
        exit;
    }
    if (isset($_POST['email'])){
        try{
            $db =new PDO('mysql:dbname=test;host=localhost;port=8889;charset=utf8','root','root');
            $stmt=$db->prepare('select * from user where Email=:email;');
            $email=htmlspecialchars($_POST['email']);

            $stmt->execute(array(':email'=>$email));

            #配列の形式を指定し連想配列を取り出す
            $result=$stmt->fetch(PDO::FETCH_ASSOC);
            
            #print($email.$_POST['pass']);
            #print($result['password']);
            if($_POST['pass']==$result['password']){
                #セッションの管理開始
                session_start();
                #サーバに保存
                #print($result['session']);
                $_SESSION['login']="ログインok";
                #echo 'ログイン認証に成功しました<br>';
                #echo '<a href "ログインフォーム-check-login.php">確認する</a>';
                
                header('Location:./oemorikensaku-tag.php');
                exit;
            }else{
                echo 'ログイン認証に失敗しました';
            }
        }catch (Exception $e){
                echo 'データベースの接続に失敗しました:';
                echo $e->getMessage();
                die(); 
            #print("あ");
        }
    }

    

    ?>
    <h1>ログイン</h1>
    <form action="" method="post">
        <table>
        <tr>
            <td><label>メールアドレス：</label></td>
            <td><input type="text" name="email"></td>
        </tr>
        <tr>
            <td><label>パスワード：</label></td>
            <td><input type ="password" name="pass"></td>
        </tr>
        </table>

        <p><input type="submit" name="button1" value="ログイン"></p>
        <p><input type="submit" name="sinki" value="初めての方はこちら"></p>
    </form>
    <label></label>  
</body>
