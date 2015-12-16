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
		$data=$this->getConveyFile(15);
		$convey=json_decode($data);
		$this->render($this->action,["convey"=>$convey,"js"=>$this->makeJS($convey)]);
	}

}