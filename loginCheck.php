<?php
require_once('header.php');

$username = $_POST['username'];
$password = $_POST['password'];

$q = "select * from user where username = '$username' and password = '$password'";
$rs = query($q);
$row = fetch($rs);
if ($row['IDuser'] !== NULL){
	$user = new user($row);
	$_SESSION['user'] = $user; 
	href("userpage.php");
}
else{
	alert("login failed");
	href("index.php");
}
?>