<?php
function getRoute(){
	if(!isset($_GET['ctrl'])){
		return array("ctrl"=>"index","act"=>"index");
	}

	if(!isset($_GET['act'])){
		return array("ctrl"=>$_GET['ctrl'],"act"=>"index"); 
	}

	return array("ctrl"=>$_GET['ctrl'],"act"=>$_GET['act']); 
}

function loadController($route){
	$filename=ROOT."/"."controllers/".ucfirst($route['ctrl'])."Controller.php";
	if(!file_exists($filename)){
		die("页面不存在!");
	}else{
		include_once $filename;
		$ctrl=ucfirst($route['ctrl'])."Controller";
		return new $ctrl($route);
	}

	
}

function execAction($route){
	$ctrl=loadController($route);
	$act="action".ucfirst($route['act']);
	if(method_exists($ctrl, $act)){
		$ctrl->$act();
	}else{
		die("页面不存在!");
	}
	
}

function getRequestType(){
	return $_SERVER['REQUEST_METHOD'];
}

function connectMysql($dbconf, $dbindex = 0){
	$dbcon = mysqli_connect($dbconf[$dbindex]['host'], $dbconf[$dbindex]['username'], $dbconf[$dbindex]['password'], $dbconf[$dbindex]['db']);
	if ($dbcon->connect_error) {
		die("数据库连接错误");
	}else{
		return $dbcon;
	}
}

function RS($msg,$url="",$res=true){
	header('Content-type: application/json');
	echo json_encode(['msg'=>$msg,'url'=>$url,'res'=>$res]);die;
}