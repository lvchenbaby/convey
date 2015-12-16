<?php	
class Controller
{
	public $controller;
	public $action;
	public $viewdir;
	public function __construct($route){
		$this->controller=$route['ctrl'];
		$this->action=$route['act'];
		$this->viewdir="/view/".$this->controller;
	}

	public function render($tpl,$params=null){
		include ROOT."/view/".$this->controller."/".$this->action.".php";
	}
}