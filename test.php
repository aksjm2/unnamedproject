<?php

$string = "예,아니요";
$token = strtok($string,",");
while ($token){
	echo $token."<br>";
	$token = strtok(",");
}


?>