<!DOCTYPE html>
<html lang="ja">

<head>
   <meta charset="UTF-8">
   <title>mission_1-</title>
</head>

<body>



   <?php
   // DB接続設定-->
   $dsn = 'データベース名';
   $user = 'ユーザー名';
   $password = 'パスワード';
   $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

   // DBのカラム作成
   $sql = "CREATE TABLE IF NOT EXISTS board"
      . " ("
      . "id INT AUTO_INCREMENT PRIMARY KEY,"
      . "name char(32),"
      . "comment TEXT,"
      . "times TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
      . ");";
   $stmt = $pdo->query($sql);

   // $_POSTが空ではない時にDBに投稿データを入力(投稿機能)
   if (!empty($_POST["name"]) && !empty($_POST["comment"])) {
      $sql = $pdo->prepare("INSERT INTO board (name, comment) VALUES (:name, :comment)");
      $sql->bindParam(':name', $name, PDO::PARAM_STR);
      $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
      $name = $_POST["name"]; //投稿する名前
      $comment = $_POST["comment"]; //投稿するコメント
      $sql->execute();
   }
   // 編集機能
   if (!empty($_POST["editNumber"]) && !empty($_POST["editName"]) && !empty($_POST["editComment"])) {
      $editId = $_POST["editNumber"]; //変更する投稿番号
      $editName = $_POST["editName"]; //変更したい名前
      $editComment = $_POST["editComment"]; //変更したいコメント
      $sql = 'UPDATE board SET name=:name,comment=:comment WHERE id=:id';
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':name', $editName, PDO::PARAM_STR);
      $stmt->bindParam(':comment', $editComment, PDO::PARAM_STR);
      $stmt->bindParam(':id', $editId, PDO::PARAM_INT);
      $stmt->execute();
   }

   // 削除機能
   if (!empty($_POST["delNumber"])) {
      $delId = $_POST["delNumber"];
      $sql = 'delete from board where id=:id';
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':id', $delId, PDO::PARAM_INT);
      $stmt->execute();
   }

   // データ出力
   $sql = 'SELECT * FROM board';
   $stmt = $pdo->query($sql);
   $results = $stmt->fetchAll();
   foreach ($results as $row) {

      echo $row['id'] . ',';
      echo $row['name'] . ',';
      echo $row['comment'] . ',';
      echo $row['times'] . '<br>';
      echo "<hr>";
   }


   ?>


   <form method="POST" action="">
      <input type="hidden" name="edit_post" value="<?php echo $_POST["editNumber"]; ?>">
      お名前：<input type="text" name="name" value="<?php echo $editContentName;  ?>"><br>
      コメント：<input type="text" name="comment" value="<?php echo $editContentComment; ?>"><br>
      <input type="submit" name="submit" value="送信する" />
   </form>

   <form method="POST" action="">
      削除対象番号：<input type="number" name="delNumber"><br>
      <input type="submit" name="delite" value="削除する" />
   </form>

   <form method="POST" action="">
      編集対象番号：<input type="number" name="editNumber"><br>
      編集するお名前：<input type="text" name="editName"><br>
      編集するコメント：<input type="text" name="editComment">
      <input type="submit" name="edit" value="編集する" />
   </form>

</body>

</html>