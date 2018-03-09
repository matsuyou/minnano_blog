<html>
<head><title>記事一覧(3)|みんなのブログ研究書</title>
<?php
   header('Content-Type: text/html; charset=UTF-8');
?>
<LINK rel="stylesheet" type="text/css" href="zakki.css">
</head>
<body>


</br>
<div class = "kizimenu">
<ul>
<li><a href='list.php'>　記事一覧　</a></li>
<li><a href='list2.php'>　　人気　　</a></li>
<li><a href='list3.php?category=1'>ブログ運営　</a></li>
<li><a href='list3.php?category=2'>日常・生活　</a></li>
<li><a href='list3.php?category=3'>作品レビュー</a></li>
<li><a href='list3.php?category=4'>商品レビュー</a></li>
<li><a href='list3.php?category=5'>　　ネタ　　</a></li>
<li><a href='list3.php?category=6'>　その他　　</a></li>
</ul>
</div>

<br/>
<br/>
<br/>

<?php

   session_start();//セッション開始

   if (isset($_GET['page'])){     //ページ数urlから取得
      $page = (int)$_GET['page'];
   }else {
      $page = 1;
   }
   
   if ($page > 1){       //表示を始める番号
      $start = ($page * 15)- 15;
   }else {
      $start = 0;
   }

   $hyouzi = 15; //1ページあたりの表示数


   //データベース接続
   require "orifunction.php"; //外部関数
   $pdo = connect_db();  //データベース接続準備

   if(isset($_GET['category'])){
      $category = $_GET['category'];  //表示するカテゴリ番号
      if ($category == 1 || $category == 2 || $category == 3 || $category == 4 || $category == 5 || $category == 6){
         $safe_category = $category;
      }else{
         $safe_category = 1;
      }
   }else{
      $safe_category = 1;
   }

   // テーブルのstartから15件のデータ取得
   $sql = "select * from kizi where category = :category order by num desc limit :start , :hyouzi";
   $stmh = $pdo->prepare($sql);
   $stmh->bindValue(':start', $start, PDO::PARAM_INT);
   $stmh->bindValue(':hyouzi', $hyouzi, PDO::PARAM_INT);
   $stmh->bindValue(':category', $safe_category, PDO::PARAM_INT);
   $stmh->execute();

   $kazu = show_data($stmh);   //orifunction関数　記事一覧の表示

   $stmh = null;
   $pdo = null;   //接続の解除


   if (!isset($_SESSION['flag'])) {   //tuika.php直接アクセス禁止用
      $_SESSION['flag'] = "a";
   }
?>

<div class = "pagenation">
<ul>
<?php
   if ($page > 1){
      $PAGE = $page - 1;
      echo ("<li><a href='list3.php?category=".$safe_category."&page=".$PAGE."'><<前</a>");
      echo"</li>\n";
   }
   if ($kazu === $hyouzi){
      $PAGE = $page + 1;
      echo ("<li><a href='list3.php?category=".$safe_category."&page=".$PAGE."'>次>></a>");
      echo"</li>\n";
   }
?>
</ul>
</div>

<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>


</body>
</html>

