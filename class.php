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

	function evaluateMessage($x,$evaluateName,$score,$unit,$type,$exists){ //evaluate����� ����ϴ� �Լ�
		if ($exists){
			if ($type == "basic"){
				echo "$x. $evaluateName"."�� ".round($score).$unit." �Դϴ�.\n";
			}
			else if ($type == "y/n" || $type == "text"){
				echo "$x. $evaluateName"."�� ���� ���� ���� ���� �򰡴� ".$score.$unit." �Դϴ�.\n";
			}
		}
		else{
			echo "$x. $evaluateName"."�� ���� �򰡰� �����ϴ�.\n";
		}
	}

	function friendCheck($IDotherUser){ //ģ������ Ȯ���ϴ� �Լ�
		$q = "select * from friend where (userID1 = $IDotherUser AND userID2 = ".$this->IDuser.") OR (userID2 = $IDotherUser  AND userID1 = ".$this->IDuser.")";
		$rs = query($q);
		if ($row = fetch($rs)){
			return true;
		}
		else{
			return false;
		}
	}

	function showFriends(){ //ģ����� �����ִ� �Լ�
		echo "<".$this->name."���� ģ�����>\n";
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

	function scoreTok($row,$myVal){ //,���� ������ �����Ͽ� �ùٸ� score���� ������ �Լ�, $row�� query����� fetch�� �ϳ��� row�� ���Ѵ�. �� $row�� �迭
		$token = strtok($row['score'],",");
		for ($y=1;$y<=$row['rateCnt'];$y++){
			if ($y == $myVal)
				break;
			$token = strtok(",");
		}
		return $token;
	}

	function showEvaluate(){
		echo "<".$this->name."���� ��>\n"; lb(1);
		$cnt = 0;
		$q = "select * from evaluate inner join evaluateType on IDevaluateType = evaluateTypeID";
		$rs = query($q);
		while ($row = fetch($rs)){ //evaluate�׸� ���� ��� �ϴ� �Լ�.
			$eq = "select * from userevaluate where userID = ".$this->IDuser." and evaluateID = ".$row['IDevaluate'];
			$ers = query($eq);
			$cnt++;
			$average = 0;
			$userEvaluateID = 0;
			$exists = false;

			if ($erow = fetch($ers)){ //�� ����� ���� ���� �ʴ� ���
				$average = $erow['sum'] / $erow['count'];
				$userEvaluateID = $erow['IDuserEvaluate'];
				$exists = true;
			}

			if (strpos($row['type'],"basic") !== false){  //basicŸ���� ��� �޽��� ���
				$this->evaluateMessage($cnt,$row['evaluateName'],$average,$row['unit'],$row['type'],$exists);
			}
			else if (strpos($row['type'],"y/n") !== false){//���ƴϿ��� ��� �޽��� ���
				if ($exists){
					$token = "";
					if ($erow['sum'] <= $erow['count']){//���� 0, �ƴϿ�� 1�� ������ �ؼ� ���� �� ���� ������ ũ�ų� ������ ���� �Ǵ��Ѵ�.(����������!)
						$token = $this->scoreTok($row,1);
					}
					else{
						$token = $this->scoreTok($row,2);
					}
				}
				$this->evaluateMessage($cnt,$row['evaluateName'],$token,$row['unit'],$row['type'],$exists);
			}
			else if (strpos($row['type'],"text") !== false){//textŸ���� ���
				//userEvaluate ���̺��� 5���� ������ ���� ū ���� ������ �ִ� ���� ã�Ƴ��� ����
				$gq = "SELECT case GREATEST(rate1, rate2, rate3, rate4, rate5)
						WHEN rate5 then 5
						WHEN rate4 then 4
						WHEN rate3 then 3
						WHEN rate2 then 2
						WHEN rate1 then 1
						ELSE 0
						END	as greatestCol
						FROM userevaluate WHERE IDuserEvaluate = $userEvaluateID";
				$grs = query($gq);
				if ($grow = fetch($grs)){
					$token = $this->scoreTok($row,$grow['greatestCol']);
				}
				
				$this->evaluateMessage($cnt,$row['evaluateName'],$token,$row['unit'],$row['type'],$exists);
			}

			if ($exists){ //�򰡰� ���� �ϴ� ��쿡�� ����� �� �� �ְ� �Ѵ�
				$this->showReply($userEvaluateID);
			}
			lb(1);
		}
	}

	function showReply($userEvaluateID){ //��� �ٴ� �κ� �Լ�!
		$q = "select * from reply where userEvaluateID = $userEvaluateID order by date desc limit 0,5"; //���� 5�������� ��������
		$rs = query($q);
		$cnt = 0;
		echo "<ul class=\"replyUl\">\n";
		while ($row = fetch($rs)){
			if ($cnt == 0) $cnt++;
			echo "\t<li>".$row['name']." - ".addslashes($row['body'])."</li>\n";
		}
		if ($cnt == 0) echo "<li>����� �����ϴ�</li>";
		echo "<input type='hidden' name='userEvaluateID' value='$userEvaluateID'>";
		echo "<input type='text' id='$userEvaluateID'>\n";
		echo "<input type='button' onClick=\"javascript:reply('$userEvaluateID');\" value='�Է�'>\n";
		echo "</ul>\n";
	}
}

class notFriend extends user{ //ģ���� �ƴ� Ŭ����
	var $IDowner;
	function __construct($IDotherUser){
		$q = "select * from user where IDuser = $IDotherUser";
		$rs = query($q);
		if ($row = fetch($rs)){
			parent::__construct($row);
		}
	}

	function addFriendButton(){
		echo "<input type=\"button\" name=\"hi\" value=\"ģ���߰���û\">\n";
	}

	/* ģ���� �ƴ� ��� �� ����� ���� �� �� �ִ°� ����� �Ѵ� */
	function showReply($userEvaluateID){
		return false;
	}

	function showFriends(){
		return;
	}
}

class friend extends user{ //ģ�� Ŭ����
	var $IDowner; //������ �� �α��� �Ǿ��ִ� ����� ID�� �������ִ�.
	function __construct($IDfriend){
		$q = "select * from user where IDuser = $IDfriend";
		$rs = query($q);
		$row = fetch($rs);
		$this->IDowner = $_SESSION['user']->IDuser;
		parent::__construct($row);
	}

	function showEvaluate(){//�򰡸� �����ִ� �Լ�. ���� �Լ��� ���������� ������ ����� �򰡸� �� �� �ִ� ���� ��ư�� ���� ������ش�.
		echo "<".$this->name."���� ��>\n"; lb(1);
		$cnt = 0;
		$x = 0;
		$q = "select * from evaluate inner join evaluateType on evaluateTypeID = IDevaluateType";
		$rs = query($q);
		echo "<form id='evalForm' action='evaluate.php' method='post'>\n";
		echo "<input type='hidden' name='from' value='".$this->IDuser."'>\n";
		echo "<input type='hidden' name='action' id='action'>\n";
		while ($row = fetch($rs)){
			$cnt++;
			$eq = "select * from userevaluate where userID = ".$this->IDuser." and evaluateID = ".$row['IDevaluate'];
			$ers = query($eq);
			$average = 0;
			$userEvaluateID = 0;
			$exists = false;
			
			if ($erow = fetch($ers)){
				$average = $erow['sum'] / $erow['count'];
				$userEvaluateID = $erow['IDuserEvaluate'];
				$exists = true;
			}

			/* Ÿ�Կ� ���� ��� �� Ȥ�� �ִ� ���� �����ִ� �κ� */
			if (strpos($row['type'],"basic") !== false){
				parent::evaluateMessage($cnt,$row['evaluateName'],$average,$row['unit'],$row['type'],$exists);
			}
			else if (strpos($row['type'],"y/n") !== false){
				$token = "";
				if ($exists){
					if ($erow['sum'] <= $erow['count']){
						$token = parent::scoreTok($row,1);
					}
					else{
						$token = parent::scoreTok($row,2);
					}
				}
				parent::evaluateMessage($cnt,$row['evaluateName'],$token,"",$row['type'],$exists);
			}
			else if (strpos($row['type'],"text") !== false){
				$gq = "SELECT case GREATEST(rate1, rate2, rate3, rate4, rate5)
						WHEN rate5 then 5
						WHEN rate4 then 4
						WHEN rate3 then 3
						WHEN rate2 then 2
						WHEN rate1 then 1
						ELSE 0
						END	as greatestCol
						FROM userevaluate WHERE IDuserEvaluate = $userEvaluateID";
				$grs = query($gq);
				if ($exists){
					$grow = fetch($grs);
					$token = parent::scoreTok($row,$grow['greatestCol']);
				}					
				parent::evaluateMessage($cnt,$row['evaluateName'],$token,$row['unit'],$row['type'],$exists);
			}
			lb(1);
			/* �� */

			
			$cq = "select * from useruserevaluate where userID1 = ".$this->IDuser." and userID2 = ".$_SESSION['user']->IDuser." and evaluateID = ".$row['IDevaluate'];
			$crs = query($cq);

			/* Ÿ�Կ� ���� �� �򰡸� �����ִ� �κ� + ���� ������ ���� �ִ� ���� �ƴ� ��츦 ó���� ���� ��*/
			/* ������� ���� �Լ��� �ٸ���! */
			if ($crow = fetch($crs)){
				if (strpos($row['type'],"basic") !== false){
					echo $crow['date']."�� ".$crow['rate'].$row['unit']."�� �ּ̽��ϴ�!";
				}
				else if (strpos($row['type'],"text") !== false || strpos($row['type'],"y/n") !== false){
					$token = parent::scoreTok($row,$crow['rate']);
					echo $crow['date']."�� $token".$row['unit']."�� �ּ̽��ϴ�!";
				}
				echo " <a onClick='javascript:reeval($x)'>����</a>\n";
				echo "<div id='layer$x' style='display:none;'>\n";
				echo "<br><input type='hidden' name='eval[$x][2]' value='1'>\n";
			}
			else{
				echo "<div id=layer$x>\n";
				echo "<input type='hidden' name='eval[$x][2]' value='0'>\n";
			}
			/* �� */

			echo "<input type='hidden' name='eval[$x][3]' value='".$row['type']."'>\n";
			echo "<input type='hidden' name='eval[$x][0]' value='".$row['IDevaluate']."'>\n";

			$token = strtok($row['score'],",");
			for ($i=1;$token;$i++){
				echo "<input type='radio' name='eval[$x][1]' value='$i'";
				if ($i == $crow['rate'])
					echo " checked ";
				echo ">".$token.$row['unit']." \n";
				$token = strtok(",");
			}

			$x++;
			echo "</div>\n";
			if ($exists){
				parent::showReply($userEvaluateID);
			}
			lb(1);
		}
		echo "<input type='submit' value='���� ���ϱ�' ></form>\n";
	}

	function showFriends(){
		return;
	}
}
?>