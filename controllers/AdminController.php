<?php
class AdminController extends Controller
{
	public function actionIndex(){
		$this->render($this->action);
	}


	//处理一次提交
	public function actionSubmit(){
		if(getRequestType()=="POST"){
			if($this->validate()){

			}else{

			}
		}
	}

	//special validate
	public function specialValidate(){

	}

	//basic validate
	public function validate(){

	}

	//创建调查
	public function actionCreateConvey(){
		if(getRequestType()=="POST"){
			$filename=ROOT."/upload/".time().rand(1,9999).".json";
			move_uploaded_file($_FILES['config']['tmp_name'], $filename);
			$data=file_get_contents($filename);
			$obj=json_decode($data);
			$res=$this->createConvey($obj,$filename);
			$obj->id=$res['insert_id'];
			$this->createQuestion($obj->questions,$res['insert_id']);

			//更新json文件
			file_put_contents($filename, json_encode($obj));

		}else{
			$this->render($this->action);
		}
	}

	//建立调查项
	private function createConvey(&$obj,$filename){
		$sql="insert into convey_list(title,configfile) values('".$obj->title."','".$filename."')";
		return iQuery($sql);
	}

	//添加调查的问题
	private function createQuestion(&$questions,$convey){
		if(is_array($questions)){
			$part="default_part";
			foreach($questions as &$v){
				$items=serialize($v->items);
				$otherfield=$v->otherfields?1:0;
				$required=$v->required?1:0;
				$sql="insert into convey_question(type,otherfield,required,title,items,part) values(".$v->type.",".$otherfield.",".$required.",'".$v->title."','".$items."','".$part."',".$convey.")";
				$id=iQuery($sql);
				$v->id=$id['insert_id'];
			}
		}else{
			foreach($questions as $k=>&$vq){
				$part=$k;
				foreach($vq as $v){
					$items=serialize($v->items);
					$otherfield=$v->otherfields?1:0;
					$required=$v->required?1:0;
					$sql="insert into convey_questions(type,otherfield,required,title,items,part,conveyid) values(".$v->type.",".$otherfield.",".$required.",'".$v->title."','".$items."','".$part."',".$convey.")";
					$id=iQuery($sql);
					$v->id=$id['insert_id'];
				}
			}
		}
	}

}