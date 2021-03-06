$(function(){

	function getAnswers(){
		var answers={};
		$(".items-wrapper").each(function(){
			try{
				var id=$(this).attr("data-id");
				var type=$(this).attr("data-type");
				var required=$(this).attr("data-required");
				var otherfield=$(this).attr("data-otherfield");
				var that = this;
				switch(parseInt(type)){
					case 1:
						var checked_item=$(this).find(".question-item:checked");
						if(checked_item.length>0){
							var idx=$(this).find(".question-item").index(checked_item.first()[0]);
							var str="";
							if(checked_item.first().hasClass("item-other")){
								str=$(this).find(".question-item-text");
							}

							answers[id]={
								idx:idx,
								txt:str,
								type:type,
								required:!!required,
								otherfield:!!otherfield
							};
						}else{
							answers[id]={
								idx:null,
								txt:null,
								type:type,
								required:!!required,
								otherfield:!!otherfield
							};
						}

					break;
					case 2:
						var checked_item=$(this).find(".question-item:checked");
						if(checked_item.length>0){
							var idx=[];
							var str="";
							var txtopt=false;

							checked_item.each(function(i){
								idx.push($(that).find(".question-item").index(this));
								if($(this).hasClass("item-other")){
									txtopt=true;
									str=$(that).find(".question-item-text").val();
								}
							});

							answers[id]={
								idx:idx,
								txt:str,
								type:type,
								txtopt:txtopt,
								required:!!required,
								otherfield:!!otherfield
							};
						}else{
							answers[id]={
								idx:null,
								txt:null,
								type:type,
								required:!!required,
								otherfield:!!otherfield
							};
						}
					break;
					case 3:
						answers[id]={
							idx:null,
							txtopt:true,
							txt:$(this).find(".question-item-text").val(),
							type:type,
							required:!!required,
							otherfield:!!otherfield
						};
					break;
				}

				answers[id].elem=this;
				answers[id].rule=$(this).attr("data-rule");
			}catch(e){
				alert("页面发生错误，请刷新后重试");				
			}
		});
		return answers;
	}


	$(".question-item").click(function(){
		if($(this).prop("checked")==true){
			var rule=$(this).parents(".items-wrapper").attr("data-rule");
			var count_chk=$(this).parents(".items-wrapper").find(".question-item:checked").length;
			if(rule!=undefined){
				if(parseInt(rule)<count_chk){
					$(this).prop("checked",false);
					if(event.preventDefault && event.stopPropagation){
						event.stopPropagation();
						event.preventDefault();
					}else{
						return false;
					}
				}
			}
		}
	});

	function validate(answers){
		var errs=[];
		for(x in answers){
			if(answers[x].required){
				if(answers[x].idx==null && answers[x].type<3){
					errs.push([answers[x].elem,1]);
				}else{
					if(answers[x].txtopt && (answers[x].txt=="" || !answers[x].txt)){
						errs.push([answers[x].elem,2]);
					}
					if(answers[x].rule){
						if(parseInt(answers[x].rule)!=answers[x].idx.length){
							errs.push([answers[x].elem],3);
						}
					}
				}

			}
		}
		return errs;
	}

	function specialValidate(answers){

	}

	function callback(res){
		if(res){
			alert(res.msg);
		}else{
			alert(res.msg);
		}
	}

	function clearErrs(){
		$('.question-title').css("color","black");
	}

	function removeDomElemFromObj(obj,field){
		if(typeof obj=="Array"){
			for(var i=0,len=obj.length;i<len;i++){
				delete obj[i][field];
			}
		}else{
			for(var x in obj){
				delete obj[x][field];
			}
		}
		return obj;
	}

	function show_error(errs){
		clearErrs();		
		for(var i=0,len=errs.length;i<len;i++){
			$(errs[i][0]).parent().prev().children("td:nth-child(2)").css("color","red");
		}
		alert("请选择必要项，填写自定义项(如需要)");
	}

	var post_url="?ctrl=index&act=RcvAns";

	$('#submit-btn').click(function(){
		$('.icon-submit-btn').attr("src","/view/index/images/tijiao1.png");
		var ans=getAnswers();
		var errs=validate(ans);
		if(errs.length>0){
			$('.icon-submit-btn').attr("src","/view/index/images/tijiao.jpg");
			show_error(errs);
		}else{
			removeDomElemFromObj(ans,"elem");
			$.post(post_url,ans,callback);
		}
	});

});