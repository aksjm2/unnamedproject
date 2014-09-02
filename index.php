<?php
require_once('header.php');
?>

<html>
<head>

<script>

function register(){
	location.href = 'join.html';
}

</script>

</head>

<body>
<form method="post" action="loginCheck.php">
<input type="text" name="username"><br>
<input type="text" name="password"><br>
<input type="submit" value="로그인"><input type="button" value="회원가입" onClick="javascript:register();">
</form>


</body>
</html>