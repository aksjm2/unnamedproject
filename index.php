<?php
require_once('header.php');
?>

<html>
<head>

<script>
	function join()
	{
		document.URL = "./join.html";
	}

</script>

<body>
<form method="post" action="loginCheck.php">
<input type="text" name="username"><br>
<input type="text" name="password"><br>
<input type="submit" value="로그인">
<input type="button" value="회원가입" onclick="join();">
</body>
</html>