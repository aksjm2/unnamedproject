//var opened = null;

function reeval(evaluateID){
	//var myForm = document.getElementById('evalForm');
	//var actionValue = document.getElementById('action');
	var layerID = "layer" + evaluateID;
	var myLayer = document.getElementById(layerID);
	/*
	if (opened !== null)
	{
		document.getElementById("layer"+opened).style.display = "none";
	}
	*/

	myLayer.style.display = "inline";
	//opened = evaluateID;
	//actionValue.value = evaluateID;

	//myForm.submit();
}

function reply(bodyID){
	var replyForm = document.getElementById('replyForm');
	var body = document.getElementById(bodyID).value;
	var bodyTxt = document.getElementById('body');
	var idTxt = document.getElementById('id');
	bodyTxt.value = body;
	idTxt.value = bodyID;
	replyForm.submit();
}
