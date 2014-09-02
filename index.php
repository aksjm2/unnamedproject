<?php
require_once('header.php');
?>

<html>
<head>

<script>
<<<<<<< HEAD

function register(){
	location.href = 'join.html';
}

</script>

</head>

=======
	function join()
	{
		document.URL = "./join.html";
	}

</script>

>>>>>>> 69d984b0c3932be586cd3d819a80a1f9a5b4eec7
<body>
<form method="post" action="loginCheck.php">
<input type="text" name="username"><br>
<input type="text" name="password"><br>
<<<<<<< HEAD
<input type="submit" value="로그인"><input type="button" value="회원가입" onClick="javascript:register();">
</form>


=======
<input type="submit" value="로그인">
<input type="button" value="회원가입" onclick="join();">
>>>>>>> 69d984b0c3932be586cd3d819a80a1f9a5b4eec7
</body>
</html>