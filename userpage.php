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

echo "�ȳ��ϼ��� ".$_SESSION['user']->name."��";

$q = "select picPath from user where username = '".$_SESSION['user']->username."'";
$rs = query($q);
while($row = fetch($rs)){
	if(strlen($row['picPath'])>0)
	{
		$picpath = $row['picPath'];
		echo "<img src=".$picpath." width='100' height=100>";
	}
	else
	{
		//������ ���� ������ ó��
	}
}
?>

<form action="logout.php">
<input type="button" value="�� ������" onClick="javascript:location.href = 'userpage.php';">
<?php if ($user instanceof notFriend) $user->addFriendButton();?>
<input type="submit" value="�α׾ƿ�">
</form>

<form action="reply.php" id="replyForm" method="post">
<input type="hidden" name="from" value="<?=$user->IDuser;?>">
<input type="hidden" name="body" id="body">
<input type="hidden" name="id" id="id">
</form>

<?php
lb(1);

$_SESSION['user']->showFriends();

$user->showEvaluate();

?>

