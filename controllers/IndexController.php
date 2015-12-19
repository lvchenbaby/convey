<?php
session_start();
class IndexController extends Controller
{
	const SURVEYID=26;

	//生成前台的模型绑定代码
	public function makeJS($data){
		if(is_array($data->questions)){
			foreach($data->questions as $q){
				$str=$this->genQuestionJS($q);
			}
		}else{
			foreach($data->questions as $p){
				foreach($p as $q){
					$str=$this->genQuestionJS($q);
				}
			}
		}
	}

	public function genQuestionJS($q){
		if($q->required){
			if($q->type==1){
				if($q->otherfields){

				}else{

				}
			}elseif($q->type==2){
				if($q->otherfields){

				}else{
					
				}
			}else{
				if($q->otherfields){

				}else{
					
				}
			}
		}else{

		}
	}

	public function getConveyFile($id){
		$sql="select * from convey_list where id=".$id;
		$rs=iQuery($sql);
		$file=$rs['result'][0]['configfile'];
		return file_get_contents($file);
	}

	public function actionIndex(){

		$fl=$this->getConveyFile(self::SURVEYID);
		$data=json_decode($fl);
		$this->render($this->action,["convey"=>$data,"js"=>$this->makeJS($data)]);
	}

	public function getQuestStas(){
		$sql="select * from convey_questions where conveyid=".self::SURVEYID;
		$res=iQuery($sql);
		return $res['result'];
	}

	public function actionStats(){
		$fl=$this->getConveyFile(self::SURVEYID);
		$data=json_decode($fl);
		$stats=$this->getQuestStas();
		$this->render($this->action,["convey"=>$data,"stats"=>$stats,"js"=>$this->makeJS($data)]);
	}

	public function validateAnswer($question,&$answer){
		if(isset($answer[$question->id])){
			$ans=$answer[$question->id];
			global $count_items;
			$count_items=count($question->items);
			if($question->otherfields){
				$count_items+=1;
			}

			switch(intval($question->type)){
				case 1:
					if(!is_null($ans['idx'])){
						$idx=intval($ans['idx']);
						if($idx>-1 && $idx<$count_items){
							if(isset($ans['txtopt']) && $ans['txtopt'] && empty($ans['txt'])){
								return false;
							}
						}else{
							return false;
						}
					}else{
						return false;
					}
				break;
				case 2:
					if(!is_null($ans['idx']) 
						&& is_array($ans['idx']) 
						&& count($ans['idx'])>0){
						// echo count(array_filter($ans['idx'],
						// 	function($var){global $count_items;echo $count_items;$res=(intval($var)>=0 && (intval($var)<$count_items));return $res;})
						// 	);
						if(count(array_filter($ans['idx'],
							function($var){global $count_items;return $var>=0 && $var<$count_items;})
							)==count($ans['idx'])){
							if(isset($ans['txtopt']) && $ans['txtopt'] && empty($ans['txt'])){
								return false;
							}
						}else{
							return false;
						}
					}else{
						return false;
					}
				break;
				case 3:
					if(empty($ans['txt'])){
						return false;
					}
				break;
			}

			$answer[$question->id]['isvalid']=true;
			return true;
		}else{
			return false;
		}
	}

	public function saveAnswer($ans){

		$sql="insert into convey_answers (ip,createdon,convey_answer,convey_id) values('". $_SERVER['REMOTE_ADDR']."',".time().",'".serialize($ans)."',".self::SURVEYID.")";
		iQuery($sql);
		foreach($ans as $k=>$a){
			$setstr="";
			if(is_array($a['idx'])){
				foreach($a['idx'] as $v){
					$setstr.=" item".$v."=item".$v."+1, ";
				}
				$setstr.=" id=id ";
			}else{
				$setstr=" item".$a['idx']."=item".$a['idx']."+1 ";
			}

			$sql="update convey_questions set ".$setstr." where id=".$k;
			iQuery($sql);
		}
	}

	public function authenticate(){
		if(isset($_COOKIE['SJIELQPLMCUHUSEUWPHED'])){
			if(getRequestType()=="GET"){
				RS("已经提交过了，非常感谢您的参与","",false);
			}else{
				$cnt=file_get_contents("php://input");
				if(!empty($cnt)){
					RS("已经提交过了，非常感谢您的参与","",false);
				}else{
					
				}
			}
		}
	}

	public function afterValidate(&$answer){
		foreach($answer as $k=>$a){
			if(!$a['isvalid']){
				unset($answer[$k]);
			}
		}

		return $answer;
	}

	public function actionRcvAns(){
		$this->authenticate();
		if(getRequestType()=="POST"){
			$ans=$_POST;
			array_walk_recursive($ans, function(&$val){if($val=="false"){$val=false;}if($val=="true")$val=true;});
			$fl=$this->getConveyFile(self::SURVEYID);
			$data=json_decode($fl);
			if(is_array($data->questions)){
				foreach($data->questions as $k=>$v){
					if(!$this->validateAnswer($v,$ans)){
						if($v->required){
							RS("错误","",false);
						}
					}
				}
			}else{
				foreach($data->questions as $part=>$questions){
					foreach($questions as $q){
						if(!$this->validateAnswer($q,$ans)){
							if($q->required){
								RS("错误","",false);
							}
						}						
					}
				}
			}
			
			$ans_filtered=$this->afterValidate($ans);
			$this->saveAnswer($ans_filtered);

			setcookie("SJIELQPLMCUHUSEUWPHED",md5(time().rand(1,999)),time()+3600*24*365);
			RS("感谢您参与该调查，我们会认真审阅您提供的信息，并保证不泄漏您的任何隐私","",true);
		}
	}

	public function actionSurveyItem(){
		$fl=$this->getConveyFile(self::SURVEYID);
		$data=json_decode($fl);
		$id=$_GET['id'];
		$sql="select * from convey_answers where id=".$id;
		$res=iQuery($sql);
		$this->render("show",['convey'=>$data,'data'=>$res['result'][0]]);
	}
}