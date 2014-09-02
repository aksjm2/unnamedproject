<?php 
require_once("header.php");

$from = $_POST['from'];
$action = $_POST['action'];
$eval = $_POST['eval'];

/*
if (strlen($action) > 0){
	$q = "select * from useruserevaluate where userID1 = $from and userID2 = ".$_SESSION['user']->IDuser." and evaluateID = $action";
	$rs = query($q);
	$row = fetch($rs);
	$rate = $row['rate'];

	$q = "delete from useruserevaluate where userID1 = $from and userID2 = ".$_SESSION['user']->IDuser." and evaluateID = $action";
	query($q);

	$q = "select * from userevaluate where userID = $from and evaluateID = $action";
	$rs = query($q);
	$row = fetch($rs);

	if ($row['count'] == 1){
		$dq = "delete from userevaluate where userID = $from and evaluateID = $action";
		query($dq);
	}
	else{
		$sum = $row['sum'];
		$sum -= $rate;

		$count = $row['count'];
		$count--;

		$uq = "update userevaluate set sum = $sum, count = $count where userID = $from and evaluateID = $action";
		query($uq);
	}
}
else{
	$eval = $_POST['eval'];

	for ($i=0;strlen($eval[$i][0]) > 0;$i++){
		if (strlen($eval[$i][1]) == 0) continue;
		$q = "select * from useruserevaluate where userID1 = $from and userID2 = ".$_SESSION['user']->IDuser." and evaluateID = ".$eval[$i][0];
		$rs = query($q);

		if ($row = fetch($rs)) {continue;}

		$q = "insert into useruserevaluate (userID1,userID2, evaluateID, rate) values ($from,".$_SESSION['user']->IDuser.",".$eval[$i][0].",".$eval[$i][1].")";
		query($q);

		$q = "select * from userevaluate where userID = $from and evaluateID = ".$eval[$i][0];

		$rs = query($q);
		if ($row = fetch($rs)){
			$sum = $row['sum'];
			$sum += $eval[$i][1];
			$count = $row['count'];
			$count++;

			$uq = "update userevaluate set sum = $sum, count = $count where userID = $from and evaluateID = ".$eval[$i][0];
			query($uq);
		}
		else{
			$q = "insert into userevaluate (userID, evaluateID, sum, count) values ($from,".$eval[$i][0].",".$eval[$i][1].",1)";
			query($q);
		}
	}
}
*/

for ($i=0;strlen($eval[$i][0]) > 0;$i++){
	if (strlen($eval[$i][1]) == 0) continue;

	$q = "select * from useruserevaluate where userID1 = $from and userID2 = ".$_SESSION['user']->IDuser." and evaluateID = ".$eval[$i][0];
	lb(1);
	$rs = query($q);

	if (($row = fetch($rs)) && $eval[$i][2] == 1){
		$rate = $row['rate'];
		if ($rate == $eval[$i][1]) continue;

		$q = "select sum from userevaluate where userID = $from and evaluateID = ".$eval[$i][0];
		$rs = query($q);
		$row = fetch($rs);

		$sum = $row['sum'] - $rate + $eval[$i][1];

		$q = "update useruserevaluate set date = CURRENT_TIMESTAMP(), rate = ".$eval[$i][1]." where userID1 = $from and userID2 = ".$_SESSION['user']->IDuser." and evaluateID = ".$eval[$i][0];
		query($q);

		$uq = "update userevaluate set sum = $sum where userID = $from and evaluateID = ".$eval[$i][0];
		query($uq);
	}
	else{
		$q = "insert into useruserevaluate (userID1,userID2, evaluateID, rate) values ($from,".$_SESSION['user']->IDuser.",".$eval[$i][0].",".$eval[$i][1].")";
		query($q);

		$q = "select * from userevaluate where userID = $from and evaluateID = ".$eval[$i][0];

		$rs = query($q);
		if ($row = fetch($rs)){
			$sum = $row['sum'];
			$sum += $eval[$i][1];
			$count = $row['count'];
			$count++;

			$uq = "update userevaluate set sum = $sum, count = $count where userID = $from and evaluateID = ".$eval[$i][0];
			query($uq);
		}
		else{
			$q = "insert into userevaluate (userID, evaluateID, sum, count) values ($from,".$eval[$i][0].",".$eval[$i][1].",1)";
			query($q);
		}
	}
}



href("userpage.php?IDuser=$from");
?>

<!--<a href="userpage.php?IDuser=<?=$from;?>">hi</a>-->