<?php
class IndexController extends Controller
{

	public function makeJS(){

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
		$this->render($this->action,["convey"=>$convey]);
	}

}