
<html>
<head><title>記事登録フォーム </title>
<?php
   header('Content-Type: text/html; charset=UTF-8');
?>
</head>


<body>
<?php

   if (isset($_GET['error'])){
      echo "エラー<br/>";
      if ($_GET['error'] == 1){
         echo "url未入力です <br/>";
      }else if ($_GET['error'] == 2){
         echo "タイトルを取得できませんでした。<br/>";
      }
   }
   
   if (isset($_COOKIE['blogtitle'])){     //クッキーがあれば取得
      $blogtitle = htmlspecialchars($_COOKIE['blogtitle']);
   }else{
      $blogtitle = '';
   }

?>

   <h4>＜入力フォーム＞</h4>
   <form action="tuika.php" method="post" onsubmit="doSomething();return false;" enctype="multipart/form-data">

   記事url：<br/>
   <input type="text" name="url" size="1000" style="width: 80%"><br/>

   ブログタイトル：<br/>
   <input type="text" name="blogtitle" size="100" value = "<?php echo $blogtitle;?>" style="width: 60%"><br/>
   <br />

   カテゴリ：<br/> 
   <label><input type="radio" name="category" value="1" /> ブログ運営</label>　　
   <label><input type="radio" name="category" value="2" /> 作品レビュー</label><br/>
   <label><input type="radio" name="category" value="3" /> ネタ</label>　　　　　
   <label><input type="radio" name="category" value="4" /> 日常・主張</label><br/>
   <label><input type="radio" name="category" value="5" /> aaa</label>　　　　　
   <label><input type="radio" name="category" value="6" checked="checked" />その他</label><br/>
   <br/>
   <label><input type="button" onclick="submit();" value="送信する" style="padding: 10px 50px;";>
   </div>

   </form>
   <br />
   <br />

</body>
</html>
