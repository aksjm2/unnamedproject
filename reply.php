<?php
require_once("header.php");

$body = $_POST['body'];
$userEvaluateID = $_POST['id'];

$q = "insert into reply (userEvaluateID, userID, name, body) values ($userEvaluateID,".$_SESSION['user']->IDuser.",'".$_SESSION['user']->name."','$body')";
query($q);
href("userpage.php?IDuser=".$_POST['from']);

?>

<!--<a href="userpage.php?IDuser=<?=$_POST['from'];?>">น้</a>-->