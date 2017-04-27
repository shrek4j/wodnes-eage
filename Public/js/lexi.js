
$(".add-word").click(function(){
	var word = $("#word").val();
	var meaning = $("#meaning").val();
	var wordRootInputs = $("input[name='wordRoot']:checked");
	var wordRoots = getWordRootStr(wordRootInputs);
	$.ajax({
	   	type: "POST",
	   	url: "saveWord",
	   	data: "word="+word+"&wordRoots="+wordRoots+"&meaning="+meaning,
	   	success: function(msg){
	   		if(msg == 'true'){
	   			$.scojs_message('添加成功！', $.scojs_message.TYPE_OK);
	   			setInterval('reloadPage()',1500);
	   		}else{
	   			$.scojs_message('添加失败！', $.scojs_message.TYPE_ERROR);
	   		}
			
	   	}
	});
});

function getWordRootStr(wordRootInputs){
	var wordRoots = "";
	for(var i=0;i<wordRootInputs.length;i++){
		wordRoots += wordRootInputs[i].value;
		if(i<wordRootInputs.length-1){
			wordRoots += "|";
		}
	}
	return wordRoots;
}

function deleteClassroom(id,name){
  if(confirm("是否删除教室："+name+"？")){
  	$.ajax({
	   	type: "POST",
	   	url: "deleteClassroom",
	   	data: "classroomId="+id,
	   	success: function(msg){
	   		if(msg == 'true'){
	   			$.scojs_message('删除成功！', $.scojs_message.TYPE_OK);
	   			setInterval('reloadPage()',1500);
	   		}else{
	   			$.scojs_message('删除失败！', $.scojs_message.TYPE_ERROR);
	   		}
			
	   	}
	});
  }
}


//------------------------word_root------------------------------------

$(".add-wordroot").click(function(){
	var wordroot = $("#wordroot").val();
	var meaning = $("#meaning").val();
	var origin = $("input[name='origin']:checked").val();
	var category = $("input[name='category']:checked").val();

	$.ajax({
	   	type: "POST",
	   	url: "saveWordRoot",
	   	data: "wordroot="+wordroot+"&meaning="+meaning+"&origin="+origin+"&category="+category,
	   	success: function(msg){
	   		if(msg == 'true'){
	   			$.scojs_message('添加成功！', $.scojs_message.TYPE_OK);
	   			setInterval('reloadPage()',1500);
	   		}else{
	   			$.scojs_message('添加失败！', $.scojs_message.TYPE_ERROR);
	   		}
			
	   	}
	});
});

