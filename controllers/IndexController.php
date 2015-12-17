<?php
class IndexController extends Controller
{

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
		$fl=$this->getConveyFile(16);
		$data=json_decode($fl);
		$this->render($this->action,["convey"=>$data,"js"=>$this->makeJS($data)]);
	}

	public function validateAnswer($question,&$answer){
		if(isset($answer[$question->id])){
			$ans=$answer[$question->id];
			$count_items=count($question->items);

			if($question->otherfields){
				$count_items+=1;
			}

			switch($question->type){
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
						if(count(array_filter($ans['idx'],
							function($var){return $var>=0 && $var<$count_items;})
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
		foreach($ans as $a){
			$sql="";
			iQuery($sql);
		}	
	}

	public function authenticate(){

	}

	public function afterValidate(&$answer){
		foreach($answer as $k=>$a){
			if(!$a['isvalid']){
				unset($answer[$k]);
			}
		}
	}

	public function actionRcvAns(){
		$this->authenticate();
		if(getRequestType()=="POST"){
			$ans=$_POST;
			$fl=$this->getConveyFile(16);
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
			var_dump($ans_filtered);die;
			$this->saveAnswer($ans_filtered);
		}
	}
}