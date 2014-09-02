<?php
function alert($msg){
	echo "<script>alert('".addslashes($msg)."');</script>";
}

function href($page){
	echo "<script>location.href = '$page';</script>";
}

function lb($x){
	for ($i=0;$i<$x;$i++)
		echo "<br>";
}
?>