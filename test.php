<?php

$string = "��,�ƴϿ�";
$token = strtok($string,",");
while ($token){
	echo $token."<br>";
	$token = strtok(",");
}


?>