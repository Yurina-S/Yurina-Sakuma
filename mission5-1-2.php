<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1-2</title>
</head>
<body>
    <?php
    // DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS mission51"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "dname char(32),"
    . "dcomment TEXT,"
    . "dpass char(32),"
    . "ddate char(32)"
    .");";
    $stmt = $pdo->query($sql);
    
    
    $name=filter_input(INPUT_POST,"name");
    $str=filter_input(INPUT_POST,"str");
    $num3=filter_input(INPUT_POST,"num3");
    $num=filter_input(INPUT_POST,"num");
    $num2=filter_input(INPUT_POST,"num2");
    $pass1=filter_input(INPUT_POST,"pass1");
    $pass2=filter_input(INPUT_POST,"pass2");
    $pass3=filter_input(INPUT_POST,"pass3");
    $date = date("Y/m/d H:i:s");

    
    if(!empty($str && $name )){
        if(empty($num3) && !empty($pass1)){
            $sql = $pdo -> prepare("INSERT INTO mission51 (dname, dcomment,dpass,ddate) 
                    VALUES (:dname, :dcomment,:dpass,:ddate)");
            $sql -> bindParam(':dname', $dname, PDO::PARAM_STR);
            $sql -> bindParam(':dcomment', $dcomment, PDO::PARAM_STR);
            $sql -> bindParam(':dpass', $dpass, PDO::PARAM_STR);
            $sql -> bindParam(':ddate', $ddate, PDO::PARAM_STR);
            $dname = $name;
            $dcomment = $str;
            $dpass = $pass1;
            $ddate=$date;
            $sql -> execute();
            
        //編集機能    
        }elseif(!empty($num3) && empty($pass1)){
            $id = $num3; //変更する投稿番号
            $dname = $name;
            $dcomment = $str;
            $ddate=$date;
            $sql = 'UPDATE mission51 SET dname=:dname,dcomment=:dcomment,ddate=:ddate WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':dname', $dname, PDO::PARAM_STR);
            $stmt->bindParam(':dcomment', $dcomment, PDO::PARAM_STR);
            $stmt->bindParam(':ddate', $ddate, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            unset($num3);
        }
            
           
    //消去機能   
    }elseif(!empty($num && $pass2)){
        //データを抽出
        $sql = 'SELECT * FROM mission51';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            if($pass2==$row['dpass'] && $num==$row['id']){
                $id = $num;
                $sql = 'delete from mission51 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }elseif($pass2!=$row['dpass'] && $num==$row['id']){
                echo "パスワードが違います"."<br>"; 
            
            }
        }
    
    //編集機能   
    }elseif(!empty($num2 && $pass3)){
        //データを抽出
        $sql = 'SELECT * FROM mission51';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            if($num2==$row['id'] && $pass3==$row['dpass']){
                    $ediname=$row['dname'];
                    $edicoment=$row['dcomment'];
                    $num3=$row['id'];
            }elseif($num2==$row['id'] && $pass3!=$row['dpass']){
                    echo "パスワードが違います"."<br>";
            } 
        }
    }
        
    ?>
    
    <form action="" method="post">
        <input type="text" name="name"
        value = "<?php if(!empty($ediname)){echo $ediname;}?>"
        placeholder="名前">
        <input type="hidden" name="num3" 
        value= "<?php if(!empty($num3)){echo $num3;}?>">
        <br>
        <input type="text" name="str"
        value = "<?php if(!empty($edicoment)){echo $edicoment;}?>"
        placeholder="コメント">
        <br>
        <input type="password" name="pass1" 
        placeholder="パスワード">
        <input type="submit" name="submit" value="送信">
        <br>
        <input type="number" name="num"
        placeholder="削除対象番号">
        <br>
        <input type="password" name="pass2" 
        placeholder="パスワード">
        <input type="submit" name="submit" value="削除">
        <br>
        <input type="number" name="num2"
        placeholder="編集対象番号">
        <br>
        <input type="password" name="pass3" 
        placeholder="パスワード">
        <input type="submit" name="submit" value="編集">
        </form>        
            
    
    <?php
    $sql = 'SELECT * FROM mission51';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].',';
        echo "　".$row['dname'];
        echo "　".$row['dcomment'];
        echo "　".$row['ddate'].'<br>';
    echo "<hr>";
    }
    
    ?>
    
