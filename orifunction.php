<?php

   function connect_db(){  //データベースの接続準備
      $dsn = 'mysql:dbname=test; host=localhost; charset=utf8';
      $user = 'root';
      $pass = '';
      $pdo = new PDO($dsn, $user, $pass);
      return $pdo;
   }

   function show_data($stmh){  //記事一覧の表示
      $kazu = 0;

      while($data = $stmh -> fetch(PDO::FETCH_BOTH)) {
         echo ("<div class = 'kizi'>");
            $category = $data['category'];               //カテゴリ表示
            echo ("<div class = 'kizi_category'>");
               if ($category == 1){
                  echo("<a href='list3.php?category=1'>ブログ運営</a>");
               }else if ($category == 2){
                  echo("<a href='list3.php?category=2'>日常・生活</a>");
               }else if ($category == 3){
                  echo("<a href='list3.php?category=3'>作品レビュー</a>");
               }else if ($category == 4){
                  echo("<a href='list3.php?category=4'>商品・サービス</a>");
               }else if ($category == 5){
                  echo("<a href='list3.php?category=5'>ネタ</a>");
               }else{
                  echo("<a href='list3.php?category=6'>その他</a>");
               }
            echo ("</div>\n");

            echo("<div class = 'kizi_date'>");        //日付、クリック数　表示
               echo(htmlspecialchars($data['date']."　"));
               echo(htmlspecialchars($data['count'])."click");
            echo("</div></br><br>\n");

            $num = htmlspecialchars($data['num']);         //タイトル表示
            $title = htmlspecialchars($data['title']);
            echo("<div class = 'kizi_title'>");
               echo("<a href='countandmove.php?num=".$num." 'target=_blank'>".$title."</a>");
            echo("</div>\n");

            echo("<div class = 'kizi_blog'>");                //ブログタイトル表示
               echo(htmlspecialchars($data['blogtitle']));
            echo("</div>");
         echo ("</div>\n\n");
         $kazu = $kazu + 1;
      }
      
      return $kazu;
   }


      
?>