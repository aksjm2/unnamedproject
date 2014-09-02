<?php
class user{
	var $IDuser, $username, $gender, $dateofbirth, $name, $registerDate, $viewCnt, $picPath;

	function __construct($row){
		$this->IDuser = $row['IDuser'];
		$this->username = $row['username'];
		$this->gender = $row['gender'];
		$this->dateofbirth = $row['dateofbirth'];
		$this->name = $row['name'];
		$this->registerDate = $row['registerDate'];
		$this->viewCnt = $row['viewCnt'];
		$this->picPath = $row['picPath'];
	}

	function friendCheck($IDotherUser){
		$q = "select * from friend where (userID1 = $IDotherUser  AND userID2 = ".$this->IDuser.") OR (userID2 = $IDotherUser  AND userID1 = ".$this->IDuser.")";
		$rs = query($q);
		if ($row = fetch($rs)){
			return true;
		}
		else{
			return false;
		}
	}

	function showFriends(){
		echo "<".$this->name."님의 친구목록>\n";
		lb(1);

		$q = "select * from friend where userID1 = ".$this->IDuser." OR userID2 = ".$this->IDuser;
		$rs = query($q);
		while ($row = fetch($rs)){
			$link = "<a href=\"userpage.php?IDuser=";
			if ($row['userID1'] == $this->IDuser)
				$link .= $row['userID2']."\">".$row['name2'];
			else
				$link .= $row['userID1']."\">".$row['name1'];
			$link .= "</a>\n";
			echo $link;
			lb(1);
		}
		lb(1);
	}

	function showEvaluate(){
		echo "<".$this->name."님의 평가>\n"; lb(1);
		$cnt = 0;
		$q = "select * from evaluate";
		$rs = query($q);
		while ($row = fetch($rs)){
			$eq = "select * from userevaluate where userID = ".$this->IDuser." and evaluateID = ".$row['IDevaluate'];
			$ers = query($eq);
			$cnt++;
			$average = 0;
			$userEvaluateID = "";
			if ($erow = fetch($ers)){
				$average = $erow['sum'] / $erow['count'];
				$userEvaluateID = $erow['IDuserEvaluate'];
			}
			echo "$cnt. ".$row['evaluateName']."은 $average".$row['unit']."입니다.\n";
			if (strlen($userEvaluateID) > 0){
				$this->showReply($userEvaluateID);
			}
			lb(1);
		}
	}

	function showReply($userEvaluateID){
		$q = "select * from reply where userEvaluateID = $userEvaluateID order by date desc limit 0,5";
		$rs = query($q);
		$cnt = 0;
		echo "<ul>\n";
		while ($row = fetch($rs)){
			if ($cnt == 0) $cnt++;
			echo "\t<li>".$row['name']." - ".addslashes($row['body'])."</li>\n";
		}
		if ($cnt == 0) echo "<li>댓글이 없습니다</li>";
		echo "<input type='hidden' name='userEvaluateID' value='$userEvaluateID'>";
		echo "<input type='text' id='$userEvaluateID'>\n";
		echo "<input type='button' onClick=\"javascript:reply('$userEvaluateID');\" value='입력'>\n";
		echo "</ul>\n";
	}
}

class notFriend extends user{
	var $IDowner;
	function __construct($IDotherUser){
		$q = "select * from user where IDuser = $IDotherUser";
		$rs = query($q);
		if ($row = fetch($rs)){
			parent::__construct($row);
		}
	}

	function addFriendButton(){
		echo "<input type=\"button\" name=\"hi\" value=\"친구추가요청\">\n";
	}

	function showReply($userEvaluateID){
		return false;
	}
}

class friend extends user{
	var $IDowner;
	function __construct($IDfriend){
		$q = "select * from user where IDuser = $IDfriend";
		$rs = query($q);
		$row = fetch($rs);
		$this->IDowner = $_SESSION['user']->IDuser;
		parent::__construct($row);
	}

	function showEvaluate(){
		echo "<".$this->name."님의 평가>\n"; lb(1);
		$cnt = 0;
		$x = 0;
		$q = "select * from evaluate";
		$rs = query($q);
		echo "<form id='evalForm' action='evaluate.php' method='post'>\n";
		echo "<input type='hidden' name='from' value='".$this->IDuser."'>\n";
		echo "<input type='hidden' name='action' id='action'>\n";
		while ($row = fetch($rs)){
			$cnt++;
			$eq = "select * from userevaluate where userID = ".$this->IDuser." and evaluateID = ".$row['IDevaluate'];
			$ers = query($eq);
			$average = 0;
			$userEvaluateID = "";
			if ($erow = fetch($ers)){
				$average = $erow['sum'] / $erow['count'];
				$userEvaluateID = $erow['IDuserEvaluate'];
			}

			echo "$cnt. ".$row['evaluateName']."은 $average".$row['unit']."입니다.\n";
			lb(1);

			$cq = "select * from useruserevaluate where userID1 = ".$this->IDuser." and userID2 = ".$_SESSION['user']->IDuser." and evaluateID = ".$row['IDevaluate'];
			$crs = query($cq);
			if ($crow = fetch($crs)){
				echo $crow['date']."에 ".$crow['rate']."점을 주셨습니다! <input type='button' value='수정' onclick='javascript:reeval($x)'>\n";
				echo "<div id=layer$x style='display:none;'>\n";
				echo "<input type='hidden' name='eval[$x][2]' value='1'>\n";
			}
			else{
				echo "<div id=layer$x>\n";
				echo "<input type='hidden' name='eval[$x][2]' value='0'>\n";
			}

			echo "<input type='hidden' name='eval[$x][0]' value='".$row['IDevaluate']."'>\n";
			for ($i=1;$i<=5;$i++){
				echo "<input type='radio' name='eval[$x][1]' value='$i'";
				if ($i == $crow['rate'])
					echo " checked ";
				echo ">".$i.$row['unit']." \n";
			}
			$x++;
			echo "</div>\n";
			if (strlen($userEvaluateID) > 0){
				parent::showReply($userEvaluateID);
			}
			lb(1);
		}
		echo "<input type='submit' value='나도 평가하기' ></form>\n";
	}
}
?>