<?PHP

function connect(){
	$link = mysql_connect('localhost', 'root', 'apmsetup');
	if (!$link){
		die('Could not connect: ' . mysql_error());
	}

	$db_selected = mysql_select_db('study', $link);
	if (!$db_selected) {
		die ('Can\'t use foo : ' . mysql_error());
	}
	mysql_set_charset('euckr',$link);
}

function query($q){
	$pos = strrpos($q, ";");
	if ($pos === false){
		$retVal = mysql_query($q);
		if (!$retVal){
			echo mysql_error().lb(1)."\n".$q.lb(1);
		}
		else{
			return $retVal;
		}
	}
	else{
		return false;
	}
}

function fetch($rs){
	$row = mysql_fetch_array($rs, MYSQL_BOTH);
	return $row;
}

connect();
?>