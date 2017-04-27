function isEmpty(field){
	if(field == null || field == undefined || field.length == 0){
		return true;
	}else{
		return false;
	}
}

function justHideToast(){
	$("#toast").hide();
}

function hideToast(){
	$("#toast").hide();
	history.back();
}

function reloadPage(){
	window.location.reload();
}