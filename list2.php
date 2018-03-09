<html>
<head><title>記事一覧(2)|みんなのブログ研究書</title>
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

   $hyouzi = 15; //1ページあたりの表示数
   
   if ($page > 1){       //表示を始める番号
      $start = ($page * $hyouzi)- $hyouzi;
   }else {
      $start = 0;
   }


   //データベース接続
   require "orifunction.php"; //外部関数
   $pdo = connect_db();  //データベース接続準備

   // テーブルのstartから15件のデータ取得
   $sql = "select * from kizi order by count desc limit :start , :hyouzi";
   $stmh = $pdo->prepare($sql);
   $stmh->bindValue(':start', $start, PDO::PARAM_INT);
   $stmh->bindValue(':hyouzi', $hyouzi, PDO::PARAM_INT);
   $stmh->execute();

   $kazu = show_data($stmh);

   $stmh = null;
   $pdo = null;   //接続の解除


   if (!isset($_SESSION['flag'])) {   //tuika.php直接アクセス禁止用
      $_SESSION['flag'] = "a";
   }


   $fp = fopen("num.txt", "r"); //numの取得
   $num = fgets($fp) - 1;   //num = 総記事数
   fclose($fp);
   $page_size = ceil($num / $hyouzi); //表示数で割った数(繰り上がり)＝総ページ数
?>


<div class = "pagenation">
<ul>
<?php
   $start = $page - 4;  //リンクページの開始番号
   if($start<1){
      $start = 1;
   }

   if ($page > 1){
      $PAGE = $page - 1;
      echo "<li><a href='list2.php?page=".$PAGE."'><<前</a>";
      echo"</li>\n";
   }

   for ($x=$start; $x <= $start+8; $x++) {    //ページネーションリンク
      echo"<li>";
      $safe_x = htmlspecialchars($x);
      if($x === $page){
         echo "<b><a href='list2.php?page=".$safe_x."'>".$safe_x."</a></b>";
      }else{
         echo"<a href='list2.php?page=".$safe_x."'>".$safe_x."</a>";
      }
      echo"</li>\n";
      
      if($x == $page_size){
         break;
      }
   }

   if ($page != $page_size){
      $PAGE = $page + 1;
      echo "<li><a href='list2.php?page=".$PAGE."'>次>></a>";
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
<br/>
<br/>
<br/>
<br/>


</body>
</html>

