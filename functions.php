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
	include_once ROOT."/"."controllers/".ucfirst($route['ctrl'])."Controller.php";
	$ctrl=ucfirst($route['ctrl'])."Controller";
	return new $ctrl($route);
}

function execAction($route){
	$ctrl=loadController($route);
	$act="action".ucfirst($route['act']);
	$ctrl->$act();
}