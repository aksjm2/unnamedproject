<?php 
require_once("header.php");

$from = $_POST['from'];
$action = $_POST['action'];
$eval = $_POST['eval'];

for ($i=0;strlen($eval[$i][0]) > 0;$i++){
	if (strlen($eval[$i][1]) == 0) continue;

	$q = "select * from useruserevaluate where userID1 = $from and userID2 = ".$_SESSION['user']->IDuser." and evaluateID = ".$eval[$i][0];
	lb(1);
	$rs = query($q);

	if (strpos($eval[$i][3],"basic" !== false){
		if (($row = fetch($rs)) && $eval[$i][2] == 1){ //���� �ϴ°��
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
		else{ //ó�� �򰡸� �� ���
			$q = "insert into useruserevaluate (userID1,userID2, evaluateID, rate) values ($from,".$_SESSION['user']->IDuser.",".$eval[$i][0].",".$eval[$i][1].")";
			query($q);

			$q = "select * from userevaluate where userID = $from and evaluateID = ".$eval[$i][0];

			$rs = query($q);

			if ($row = fetch($rs)){ //�ٸ� ����� ���� ����� ���� �׸� ���� �̹� �򰡸� �Ѱ��
				$sum = $row['sum'];
				$sum += $eval[$i][1];
				$count = $row['count'];
				$count++;

				$uq = "update userevaluate set sum = $sum, count = $count where userID = $from and evaluateID = ".$eval[$i][0];
				query($uq);
			}
			else{ //�ٸ� ����� ���� ����� ���� �׸� ���� �򰡸� ���� ���� ���
				$q = "insert into userevaluate (userID, evaluateID, sum, count) values ($from,".$eval[$i][0].",".$eval[$i][1].",1)";
				query($q);
			}
		}
	}
	else if (strpos($eval[$i][3],"text") != false){ //�� ���� text�� ���
		if (($row = fetch($rs)) && $eval[$i][2] == 1){ //���� �ϴ°��
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
		else{ //ó�� �򰡸� �� ���
			$q = "insert into useruserevaluate (userID1,userID2, evaluateID, rate) values ($from,".$_SESSION['user']->IDuser.",".$eval[$i][0].",".$eval[$i][1].")";
			query($q);

			$q = "select * from userevaluate where userID = $from and evaluateID = ".$eval[$i][0];
			$rs = query($q);
			$fieldName = "rate".$eval[$i][1];

			if ($row = fetch($rs)){
				$uq = "update userevaluate set $fieldName = $fieldName + 1 where userID = $from and EvaluateID = ".$eval[$i][0];
				query($uq);
			}
			else{
				$q = "insert into userevaluate (userId, evaluateID, $fieldName) values ($from,".$eval[$i][0].",1)";
				query($q);
			}
		}
	}
}

href("userpage.php?IDuser=$from");
?>

<!--<a href="userpage.php?IDuser=<?=$from;?>">hi</a>-->