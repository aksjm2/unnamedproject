<?php
require_once('header.php');
echo "<script type=\"text/javascript\" src=\"script.js\"></script>";

if ($_SESSION['user'] === NULL)
	href("index.php");

if (strlen($_GET['IDuser']) > 0 && $_GET['IDuser'] != $_SESSION['user']->IDuser){
	if ($_SESSION['user']->friendCheck($_GET['IDuser'])){
		$user = new friend($_GET['IDuser']);
	}
	else{
		$user = new notFriend($_GET['IDuser']);
	}
}
else{
	$user = $_SESSION['user'];
}

echo "¾È³çÇÏ¼¼¿ä ".$_SESSION['user']->name."´Ô";
?>

<form action="logout.php">
<input type="button" value="³» ÆäÀÌÁö" onClick="javascript:location.href = 'userpage.php';">
<?php if ($user instanceof notFriend) $user->addFriendButton();?>
<input type="submit" value="·Î±×¾Æ¿ô">
</form>

<form action="reply.php" id="replyForm" method="post">
<input type="hidden" name="from" value="<?=$user->IDuser;?>">
<input type="hidden" name="body" id="body">
<input type="hidden" name="id" id="id">
</form>

<?php
lb(1);

$user->showFriends();

$user->showEvaluate();

?>

