<html>
<head><title>backup</title>
<?php
   //kizi全データをテキストファイルに出力
   header('Content-Type: text/html; charset=UTF-8');
?>
</head>

<body>
<?php

   // MySQLに対する処理
   require "orifunction.php"; //外部関数
   $pdo = connect_db();  //データベース接続準備

   $sql = "select * from kizi order by num asc";
   $stmh = $pdo->query($sql) or die("失敗しました");

   $fp = fopen("kizi.txt","w");
   $fp = fopen("kizi.txt","a");
   while($data = $stmh -> fetch(PDO::FETCH_BOTH)) {   //while($data = mysql_fetch_array($result)) {
      fwrite($fp,$data['num']."\r\n");
      fwrite($fp,$data['url']."\r\n");
      fwrite($fp,$data['title']."\r\n");
      fwrite($fp,$data['blogtitle']."\r\n");
      fwrite($fp,$data['date']."\r\n");
      fwrite($fp,$data['category']."\r\n");
      fwrite($fp,$data['count']."\r\n");
      fwrite($fp,"\r\n\r\n");
   }
   fclose($fp);


   $stmh = null;
   $pdo = null;   //接続の解除


   echo("バックアップ完了");
?>
</body>
</html>

