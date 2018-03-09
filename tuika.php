<?php //記事の追加処理

   if(($_POST['url']) === '' || isset($_POST['url'])!=1) {
      $error = 1;
      header("Location: http://localhost/form.php?error=1");  //url未入力の場合リダイレクト
   }else{
      $url = $_POST['url'];   //urlの取得
      unset($_POST['url']);
      $safe_url = htmlspecialchars($url);   //サニタイジング
      
      if(isset($_POST['blogtitle'])!=1){
         $safe_blogtitle = '';                  //空欄で設定
         setcookie('blogtitle',$safe_blogtitle,time()-1);   //クッキー削除
      }else{
         $blogtitle = $_POST['blogtitle'];     //ブログタイトルの取得
         $safe_blogtitle = htmlspecialchars($blogtitle);    //サニタイジング
         setcookie('blogtitle',$safe_blogtitle,time()+60+60*24*31); //クッキー1ヶ月
      }
      
      $category = $_POST['category'];   //categoryの取得

      date_default_timezone_set('Asia/Tokyo');
      $Date = date('Y/m/d');

      $fp = fopen("num.txt", "r"); //numの取得
      $num = fgets($fp);
      fclose($fp);

      //ソースの取得
      $source = @file_get_contents($safe_url);

      //文字コードをUTF-8に変換(mb_convert_encoding)し、正規表現でタイトルを抽出(preg_match)
      if (preg_match('/<title>(.*?)<\/title>/i', mb_convert_encoding($source, 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS'), $result)) {

         $title = $result[1]; 
         $safe_title = htmlspecialchars($title);   //サニタイジング

         // MySQLに対する処理
         require "orifunction.php"; //外部関数
         $pdo = connect_db();  //データベース接続準備

         //　＊＊＊連投対策＊＊＊＊＊＊＊＊＊＊＊/////
/*  旧
         $sql = "select * from kizi where num = :num";
         $stmh = $pdo->prepare($sql);
         $NUM = $num - 1;
         $stmh->bindValue(':num', $NUM, PDO::PARAM_INT);
         $stmh->execute();
         $data = $stmh->fetch(PDO::FETCH_BOTH);
         $URL = $data['url'];
         if ( $URL === $url){   //前回と同じurl送信した場合
*/

         $sql = "select * from kizi where title = :title";
         $stmh = $pdo->prepare($sql);
         $stmh->bindValue(':title', $title, PDO::PARAM_INT);
         $stmh->execute();         
         $count_row = $stmh->rowCount();
         
         if ( $count_row != 0){   //ブログタイトル被りあり

            header("Location: http://localhost/form.php?error=3"); 
         }else{
            $count = 0;

            $sql = "insert into kizi (num, url, title, blogtitle, date, category, count) values(?, ?, ?, ?, ?, ?, ?)";
            $stmh = $pdo->prepare($sql);      //PDO(プリペアドステートメント)

            $stmh->bindValue(1, $num);
            $stmh->bindValue(2, $safe_url);
            $stmh->bindValue(3, $safe_title);
            $stmh->bindValue(4, $safe_blogtitle);
            $stmh->bindValue(5, $Date);
            $stmh->bindValue(6, $category);
            $stmh->bindValue(7, $count);

            $stmh->execute();     //prepareのsql文実行

            $stmh = null;
            $pdo = null;   //接続の解除

            $fp = fopen("num.txt", "w");    //通し番号の更新
            $num = $num + 1;
            fwrite($fp, $num);
            fclose($fp);
            
                        /////////自動ツイート//////////////////////////////////////
            // Consumer key
            $consumer_key = "〇〇〇";
            // Consumer secret
            $consumer_secret = "○○○";
            // Access token
            $access_token = "○○○";
            // Access token secret
            $access_token_secret = "○○○";

            //接続
            $connection = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret); 

            if($safe_blogtitle != ""){
                $tweet = "『".$safe_blogtitle."』が更新されました\r";
                $tweet.= $safe_url;
            }else{
                $tweet = "記事が更新されました\r";
                $tweet.= $safe_url;
            }
            $request = $connection->post("statuses/update", array("status"=> $tweet ));
            ////////////////////////////////////////////////////////////////////////////////


            header("Location: http://localhost/list.php");
         }
      }else{
         header("Location: http://localhost/form.php?error=2");  //タイトル取得できない場合リダイレクト
      }
   }

?>
