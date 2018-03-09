<?php
   //閲覧数カウント処理　ブログページへ遷移
   session_start(); //セッション開始

   if(!isset($_SESSION['flag'])){        //前ページからのセッションない場合
      header("Location: http://localhost/list.php?error=1");  //リダイレクト
   }else{
      if(isset($_GET['num'])) {
         $num = $_GET['num'];   //変数の取得
         $num = htmlspecialchars($num); 

         // MySQLに対する処理
         require "orifunction.php"; //外部関数
         $pdo = connect_db();  //データベース接続準備

         $sql = "select * from kizi where num = :num";
         $stmh = $pdo->prepare($sql);
         $stmh->bindValue(':num', $num, PDO::PARAM_INT);
         $stmh->execute();
         $data = $stmh->fetch(PDO::FETCH_BOTH);

         $num = $data['num'];
         $url = $data['url'];
         $count = $data['count'] + 1;

         $sql = "update kizi set count = :count where num = :num";
         $stmh = $pdo->prepare($sql);
         $stmh->bindValue(':num', $num, PDO::PARAM_INT);
         $stmh->bindValue(':count', $count, PDO::PARAM_INT);
         $stmh->execute();

         $stmh = null;
         $pdo = null;   //接続の解除

         $safe_url = htmlspecialchars($url);   //サニタイジング
         header("Location: $safe_url"); //リダイレクト


         if(!isset($url)){    //異常なnum設定されてた場合
            header("Location: http://localhost/list.php?error=1");  //リダイレクト
         }
      }else{
         header("Location: http://localhost/list.php");
      }
   }
?>

