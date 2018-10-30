<head>
 <meta charset="UTF-8">
</head>

<?php
//データベースへの接続
$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password);

//データベース内にテーブルを作成。
$sql="CREATE TABLE mission_4"
."("
."id INT,"
."name char(32),"
."comment varchar(32),"
."password varchar(32)"
.");";
$stmt=$pdo->query($sql);

/*$sql='SHOW TABLES';
$result=$pdo->query($sql);
foreach($result as $row){
echo $row[0];
echo '<br>';
}
echo"<hr>";

//テーブルの中身を確認するため、意図した内容のテーブルが作成されているか確認
$sql='SHOW CREATE TABLE mission_4';
$result=$pdo->query($sql);
foreach($result as $row){
print_r($row);
}
echo"<hr>";*/

//削除機能の導入
$sql='SELECT*FROM mission_4';
if(!empty($_POST['delete']) && !empty($_POST['pass_del'])){
$results=$pdo->query($sql);
	foreach($results as $row){
		if($_POST['delete']==$row['id']){
			if($_POST['pass_del']==$row['password']){
			$delete=$_POST['delete'];
			}
			else{
			echo "パスワードが違います。";
			}
		}
	}
}

//編集機能の導入
if(!empty($_POST['edit']) && !empty($_POST['pass_edit'])){
$results=$pdo->query($sql);
	foreach($results as $row){
		if($_POST['edit']==$row['id']){
			if($_POST['pass_edit']==$row['password']){
			$name_form=$row['name'];
			$comment_form=$row['comment'];
			$hidden_form=$row['id'];
			}
			else{
			echo "パスワードが違います。";
			}
		}
	}
}
//php終了
?>


<!--名前のテキストボックス-->
<form method="post" action="mission_4.php">
<input type="text" name="name" placeholder="名前"
 value="<?php echo $name_form ?>"
><br>

<!--コメントのテキストボックス-->
<input type="text" name="comment" placeholder="コメント" 
 value="<?php echo $comment_form ?>"
><br>

<!--パスワードのテキストボックス-->
<input type="text" name="password" placeholder="パスワード" >
<input type="submit" value="送信">
<br>

<!--編集モードの隠しテキストボックス-->
<input type="hidden" name="hidden" 
 value="<?php echo $hidden_form ?>"
>
</form>
<br>

<!--削除機能のテキストボックス-->
<form method="post" action="mission_4.php">
<input type="text" name="delete" placeholder="削除対象番号">
<br>
<input type="text" name="pass_del" placeholder="パスワード">
<input type="submit" value="削除">
</form>

<!--編集機能のテキストボックス-->
<form method="post" action="mission_4.php">
<input type="text" name="edit" placeholder="編集対象番号">
<br>
<input type="text" name="pass_edit" placeholder="パスワード">
<input type="submit" value="編集">
</form>

<!--==========================php開始==========================-->
<?php
//idの最大値を取る。
$max = $pdo -> query("SELECT MAX(id) FROM mission_4")->fetchColumn();
$id_next=0;
$id_next=$max+1;//投稿されている番号の次の番号を表す。(1の投稿があれば最大値は1、次は2の投稿したいので最大値に+1する)

//insertを行ってデータを入力
if(!empty($_POST['name'])&& !empty($_POST['comment'])&& empty($_POST['hidden'])&& !empty($_POST['password'])){
 $sql=$pdo->prepare("INSERT INTO mission_4 (id,name,comment,password)VALUES(:id,:name,:comment,:password)");
//insertはテキストファイルに投稿を書いておくようなもん。名前、コメントなどの情報を入れている。
 $sql->bindParam(':id',$id,PDO::PARAM_INT);
 $sql->bindParam(':name',$name,PDO::PARAM_STR);
 $sql->bindParam(':comment',$comment,PDO::PARAM_STR);
 $sql->bindParam(':password',$password,PDO::PARAM_STR);
 $id=$id_next;
 $name=$_POST['name'];
 $comment=$_POST['comment'];
 $password=$_POST['password'];
 $sql->execute();
}
elseif(!empty($_POST['name'])&& !empty($_POST['comment'])&& !empty($_POST['hidden'])&& !empty($_POST['password'])){
 //入力したデータをupdateによって編集する
 $nm=$_POST['name'];
 $kome=$_POST['comment'];
 $pass=$_POST['password'];
 $sql="update mission_4 set name='$nm',comment='$kome',password='$pass'where id={$_POST['hidden']}";
 $result=$pdo->query($sql);
}

if(!empty($delete)){
 //入力したデータをdeleteによって削除する
 $sql="delete from mission_4 where id=$delete";
 $result=$pdo->query($sql);
}

//入力したデータをselectによって表示する
$sql='SELECT*FROM mission_4 ORDER BY id ASC';
$results=$pdo->query($sql);
foreach($results as $row){
 echo $row['id'].',';
 echo $row['name'].',';
 echo $row['comment'].'<br>';
}
//php終了
?>