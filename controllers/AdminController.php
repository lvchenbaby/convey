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

	//调查列表
	public function actionListSurvey(){
		if(isset($_GET['ajax']) && $_GET['ajax']==1){
			$page=$_POST['page'];
			$page_size=$_POST['rows'];
			$start=($page-1)*$page_size;
			$sql="select SQL_CALC_FOUND_ROWS id,ip,DATE_FORMAT(FROM_UNIXTIME(createdon),'%Y-%m-%d %H:%i:%s') as createdon from convey_answers limit ".$start.",".$page_size;
			$res=iQuery($sql);
			responseJSON(['total'=>$res['total_rows'],'rows'=>$res['result']]);

		}else{
			$this->render("list_survey");
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
		$filename=str_replace("\\", "/", $filename);
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


	public function actionCreateQuestion(){
		$this->render("create_question");
	}


	public function actionSurveyList(){
		$sql="select * from convey_list";
		$res=iQuery($sql);
		responseJSON($res['result']);
	}


	public function actionGetQuestions(){
		$surveyid=$_GET['id'];
		$page=$_POST['page'];
		$page_size=$_POST['rows'];
		$start=($page-1)*$page_size;
		$sql="select SQL_CALC_FOUND_ROWS * from convey_questions where conveyid=".$surveyid." limit ".$start.",".$page_size;
		$res=iQuery($sql);

		foreach($res['result'] as &$v){
			$v['items']=unserialize($v['items']);
		}

		responseJSON(['total'=>$res['total_rows'],'rows'=>$res['result']]);
	}

	public function actionDelete(){
		$id=$_POST['id'];
		$sql="delete from convey_answers where id=".$id;
		iQuery($sql);
		responseJSON(['success'=>true]);
	}
}