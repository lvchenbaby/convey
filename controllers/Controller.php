<?php	
class Controller
{
	public $controller;
	public $action;

	public function __construct($route){
		$this->controller=$route['ctrl'];
		$this->action=$route['act'];
	}

	public function render($tpl,$params=null){
		include ROOT."/view/".$this->controller."/".$this->action.".php";
	}
}