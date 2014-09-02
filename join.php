<?php
require_once('header.php');
?>

<html>
 <head>
  <title>:: Join Page ::</title>
  <h2>check</h2>
 </head>
 <body>
<?php
$fid = $_POST['firstname']."@".$_POST['lastname'];
$upw = $_POST['pw'];
$uname = $_POST['name'];
$ubirth = date("Y-m-d",strtotime($_POST['year']."-".$_POST['month']."-".$_POST['day']));
$gender = $_POST['gender'];
date_default_timezone_set("Asia/Seoul");
$registerdate = date("Y-m-d h:i:s",time());

$tmp_file = $_FILES['upload_file']['tmp_name'];

$temp = explode(".",$_FILES['upload_file']['name']);
$ext = end($temp);

$file_path = './image/'.$fid.".".$ext;

$r = move_uploaded_file($tmp_file, $file_path);

if($r)
{
	echo "uploaded";
}
else
{
	echo "error";
}

$q = "insert into user(username,password,gender,dateofbirth,name,registerDate,picPath) values('$fid','$upw','$gender','$ubirth','$uname','$registerdate','$file_path')";
query($q);
href('index.php');
?>
</body>
</html>