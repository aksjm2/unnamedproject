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

	function evaluateMessage($x,$evaluateName,$score,$unit,$type,$exists){ //evaluate결과를 출력하는 함수
		if ($exists){
			if ($type == "basic"){
				echo "$x. $evaluateName"."은 ".round($score).$unit." 입니다.\n";
			}
			else if ($type == "y/n" || $type == "text"){
				echo "$x. $evaluateName"."에 대해 가장 많이 받은 평가는 ".$score.$unit." 입니다.\n";
			}
		}
		else{
			echo "$x. $evaluateName"."에 대한 평가가 없습니다.\n";
		}
	}

	function friendCheck($IDotherUser){ //친구인지 확인하는 함수
		$q = "select * from friend where (userID1 = $IDotherUser AND userID2 = ".$this->IDuser.") OR (userID2 = $IDotherUser  AND userID1 = ".$this->IDuser.")";
		$rs = query($q);
		if ($row = fetch($rs)){
			return true;
		}
		else{
			return false;
		}
	}

	function showFriends(){ //친구목록 보여주는 함수
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

	function scoreTok($row,$myVal){ //,으로 구분자 설정하여 올바른 score값을 따오는 함수, $row는 query결과를 fetch한 하나의 row를 뜻한다. 즉 $row는 배열
		$token = strtok($row['score'],",");
		for ($y=1;$y<=$row['rateCnt'];$y++){
			if ($y == $myVal)
				break;
			$token = strtok(",");
		}
		return $token;
	}

	function showEvaluate(){
		echo "<".$this->name."님의 평가>\n"; lb(1);
		$cnt = 0;
		$q = "select * from evaluate inner join evaluateType on IDevaluateType = evaluateTypeID";
		$rs = query($q);
		while ($row = fetch($rs)){ //evaluate항목 별로 출력 하는 함수.
			$eq = "select * from userevaluate where userID = ".$this->IDuser." and evaluateID = ".$row['IDevaluate'];
			$ers = query($eq);
			$cnt++;
			$average = 0;
			$userEvaluateID = 0;
			$exists = false;

			if ($erow = fetch($ers)){ //평가 결과가 존재 하지 않는 경우
				$average = $erow['sum'] / $erow['count'];
				$userEvaluateID = $erow['IDuserEvaluate'];
				$exists = true;
			}

			if (strpos($row['type'],"basic") !== false){  //basic타입의 경우 메시지 출력
				$this->evaluateMessage($cnt,$row['evaluateName'],$average,$row['unit'],$row['type'],$exists);
			}
			else if (strpos($row['type'],"y/n") !== false){//예아니요인 경우 메시지 출력
				if ($exists){
					$token = "";
					if ($erow['sum'] <= $erow['count']){//예는 0, 아니요는 1로 생각을 해서 평가한 수 보다 총합이 크거나 같으면 예로 판단한다.(긍정적으로!)
						$token = $this->scoreTok($row,1);
					}
					else{
						$token = $this->scoreTok($row,2);
					}
				}
				$this->evaluateMessage($cnt,$row['evaluateName'],$token,$row['unit'],$row['type'],$exists);
			}
			else if (strpos($row['type'],"text") !== false){//text타입인 경우
				//userEvaluate 테이블의 5개의 열에서 가장 큰 값을 가지고 있는 열을 찾아내는 쿼리
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

			if ($exists){ //평가가 존재 하는 경우에만 댓글을 달 수 있게 한다
				$this->showReply($userEvaluateID);
			}
			lb(1);
		}
	}

	function showReply($userEvaluateID){ //댓글 다는 부분 함수!
		$q = "select * from reply where userEvaluateID = $userEvaluateID order by date desc limit 0,5"; //현재 5개까지만 보여진다
		$rs = query($q);
		$cnt = 0;
		echo "<ul class=\"replyUl\">\n";
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

class notFriend extends user{ //친구가 아닌 클래스
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

	/* 친구가 아닌 경우 그 사람에 대해 볼 수 있는게 없어야 한다 */
	function showReply($userEvaluateID){
		return false;
	}

	function showFriends(){
		return;
	}
}

class friend extends user{ //친구 클래스
	var $IDowner; //소유자 즉 로그인 되어있는 사람의 ID를 가지고있다.
	function __construct($IDfriend){
		$q = "select * from user where IDuser = $IDfriend";
		$rs = query($q);
		$row = fetch($rs);
		$this->IDowner = $_SESSION['user']->IDuser;
		parent::__construct($row);
	}

	function showEvaluate(){//평가를 보여주는 함수. 기존 함수와 유사하지만 실제로 사람이 평가를 할 수 있는 라디오 버튼을 같이 출력해준다.
		echo "<".$this->name."님의 평가>\n"; lb(1);
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

			/* 타입에 따라서 평균 값 혹은 최대 값을 구해주는 부분 */
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
			/* 끝 */

			
			$cq = "select * from useruserevaluate where userID1 = ".$this->IDuser." and userID2 = ".$_SESSION['user']->IDuser." and evaluateID = ".$row['IDevaluate'];
			$crs = query($cq);

			/* 타입에 따라서 내 평가를 보여주는 부분 + 내가 선택한 적이 있는 경우와 아닌 경우를 처리도 같이 함*/
			/* 여기부터 기존 함수와 다르다! */
			if ($crow = fetch($crs)){
				if (strpos($row['type'],"basic") !== false){
					echo $crow['date']."에 ".$crow['rate'].$row['unit']."을 주셨습니다!";
				}
				else if (strpos($row['type'],"text") !== false || strpos($row['type'],"y/n") !== false){
					$token = parent::scoreTok($row,$crow['rate']);
					echo $crow['date']."에 $token".$row['unit']."을 주셨습니다!";
				}
				echo " <a onClick='javascript:reeval($x)'>수정</a>\n";
				echo "<div id='layer$x' style='display:none;'>\n";
				echo "<br><input type='hidden' name='eval[$x][2]' value='1'>\n";
			}
			else{
				echo "<div id=layer$x>\n";
				echo "<input type='hidden' name='eval[$x][2]' value='0'>\n";
			}
			/* 끝 */

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
		echo "<input type='submit' value='나도 평가하기' ></form>\n";
	}

	function showFriends(){
		return;
	}
}
?>