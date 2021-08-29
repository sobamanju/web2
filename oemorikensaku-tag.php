<!DOCTYPE html>
<html lang="ja">
<h1>検索</h1>
<head>
    <meta charset ="utf-8">
</head>
<body>
    <?php $db =new PDO('mysql:dbname=test;host=localhost;port=8889;charset=utf8','root','root');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);?>
    <form action="" method="post">
        <table>
        <tr>
            <td>
            <label>キーワードを入力(ひらがなで入力)：</label>
            </td>
            <td><input type="text" name="keyword"></td>
        </tr>
        <tr>
            <td>
            <label>検索位置</label>
            </td>
            <td>
            <input type="text" name="r" list="range">
            <datalist id="range">
            <option value="含む">含む</option>
            <option value="前方一致">前方一致</option>
            <option value="後方一致">後方一致</option>
            </td>
            <!--<input type="submit" name="sr">-->
        </tr>
        <?php $sql="select distinct category from category;";
            $stmt = $db->prepare($sql);
            $stmt->execute();
             while($row1 = $stmt->fetch()){
		    $rows1[] = $row1;
	        }?>
        <?php $sql="select distinct category2 from category;";
            $stmt = $db->prepare($sql);
            $stmt->execute();
             while($row1 = $stmt->fetch()){
		    $rows1[] = $row1;
	        }?>
        <tr>
            <td>
                <label>絵の特徴を選択</label>
            </td>
            <td>
                <input type="text" name="t" list="category">
                <datalist id="category">
                <?php foreach ($rows1 as $row1) {?>
                    <option 
                    value="<?php echo htmlspecialchars($row1['category'],ENT_QUOTES,'UTF-8'); ?>">
                    <?php echo htmlspecialchars($row1['category'],ENT_QUOTES,'UTF-8'); ?>
                    </option>
                <?php }?>
            </td>
        </tr>
        <tr>
            <td>
                <label>細かく検索</label>
            </td>
            <td>
                <input type="text" name="t" list="category2">
                <datalist id="category2">
                <?php foreach ($rows1 as $row1) {?>
                    <option 
                    value="<?php echo htmlspecialchars($row1['category2'],ENT_QUOTES,'UTF-8'); ?>">
                    <?php echo htmlspecialchars($row1['category2'],ENT_QUOTES,'UTF-8'); ?>
                    </option>
                <?php }?>
            </td>
        </tr>
        </table>
        <!--<p>
            <label>協力プレイ</label>
            <input type="text" name="m" list="mostly">
            <datalist id="mostly">
            <option value="すべて">すべて</option>
            <option value="協力限定お題">協力限定お題</option>
            <input type="submit" name="coo">
        </p>-->
        <p>
            <input type="submit" name="choose" value="検索">
        </p>
        <p>
            <input type="submit" name="lo" value="ログアウト">
        </p>
        
    </form>
    <?php
     #セッション使用の宣言
     session_start();
    try{
       
        #セッションデータを格納し、
        if(!isset($_SESSION["login"])){
            header('Location:./loginform.php');
            exit;

        }
        #ログアウトボタンかを判定
        if($_POST['lo']){
            session_destroy();
            header('Location:./loginform.php');
            exit;
        }
        #echo "接続成功";
        $sql="select * from odai200 where odaiHiragana like :keyword and tag like :tag;";
        $stmt = $db->prepare($sql);
        if (isset($_POST['keyword'])){
            if($_POST['r']=='前方一致'){
                $stmt->bindValue(':keyword', $_POST['keyword'].'%');
            }
            elseif($_POST['r']=='後方一致'){
                $stmt->bindValue(':keyword', '%'.$_POST['keyword']);
            }
            else{
                $stmt->bindValue(':keyword', '%'.$_POST['keyword'].'%');
            }
        }
        else{
            $stmt->bindValue(':keyword','%');
        }
        if(isset($_POST['t'])){
            $stmt->bindValue(':keyword','%');
            $stmt->bindValue(':tag', '%'.$_POST['t'].'%');
        }
        else{
            $stmt->bindValue(':tag','%');
        }
        /*細かく検索*/
        if(isset($_POST['t'])){
            $stmt->bindValue(':keyword','%');
            $stmt->bindValue(':tag', '%'.$_POST['t'].'%');
        }
        else{
            $stmt->bindValue(':tag','%');
        }
        
        /*if (isset($_POST['keyword'])){
            $sql="select * from categoryTest;";
            $stmt = $db->prepare($sql);
            
        }
        else{
            $sql="select * from odai2tag;";
            $stmt = $db->prepare($sql);
            
        }*/
            $stmt->execute();
            #echo "お題取得";

            //レコード件数取得
            $row_count = $stmt->rowCount();
            #echo $row_count;
            while($row = $stmt->fetch()){
		    $rows[] = $row;
	        }
        #echo "ループ";
        //データベース接続切断
	    $dbh = null;
        #echo "切断"; 
    
    ?>
    <!--border='1'は枠の太さ　0になったら枠の表示は無し-->
    <table border='1'>
    <tr><td>ID</td><td>odaiKanji</td><td>odaiHiragana</td><td>tag</td></tr>
    <?php
        foreach($rows as $row){
    ?>
    <tr> 
	    <td><?php echo $row['ID']; ?></td> 
	    <td><?php echo htmlspecialchars($row['odaiKanji'],ENT_QUOTES,'UTF-8'); ?></td> 
        <td><?php echo htmlspecialchars($row['odaiHiragana'],ENT_QUOTES,'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars($row['tag'],ENT_QUOTES,'UTF-8'); ?></td>
    </tr>
    <?php   
        }
    ?>
    
    </table>
    <?php       
        #検索結果が見つからなかった場合
    }catch(PDOException $e){
        print('Error:'.$e->getMessage());
        die();
    }
    ?>

    
</body>
</html>
    