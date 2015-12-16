$(function(){

	function getQuestions(){
		
	}

	function validate(){

	}

	function makeJSON(){

	}

	function callback(res){
		
	}

	function show_error(){

	}

	var post_url="";

	$('#submit-btn').click(function(){
		if(validate()){
			$.post(post_url,makeJSON(),callback);
		}else{
			show_error();
		}
	});

});