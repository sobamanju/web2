<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset ="utf-8">
</head>
<body>
    <?php
        #userテーブルの有無確認
        if(isset($_POST['email'])) {
            try{
                $db =new PDO('mysql:dbname=test;host=localhost;port=8889;charset=utf8','root','root');
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                #POSTで送られた会員情報の登録
                #実行後にSQLの実行結果に関する情報を得たい
                $stmt = $db->prepare("INSERT INTO USER(Email,password) VALUES(:email,:password);");
                $stmt->bindParam(':email', $_POST['email']);
                $stmt->bindParam(':password', $_POST['password']);
                $ret = $stmt->execute();
                if (!$ret) {
                    print "error";
                }
                echo '登録完了<br>';
                echo '<a href="loginform.php">ここ</a>をクリックしてログインしなおしてください<br>';
                #header('Location:./loginform.php');
                #exit;
                
            }catch(Exception $e){
                echo '登録失敗しました<br>';
                echo '別のメールアドレスを入力してください';

                #エラー番号
                #echo $e->getMessage();
                #メッセージを出力し、現在のスクリプトを終了する
                #die(); 
            }
        }
    ?>

<h1>新規会員登録</h1>
    <form action="kaiin.php" method="post">
        <table>
        <tr>
            <td>メールアドレスを入力：</td>
            <td><input type="text" name="email" required></td>
        </tr>
        <tr>
            <td>パスワードを入力：</td>
            <td><input type="password" name="password" required></td>
        </tr>
        </table>
        <p><input type="submit" value="登録"></p>
    </form>
</body>
</html>