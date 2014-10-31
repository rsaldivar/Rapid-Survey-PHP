<?php
class Controller extends common{

	protected $_model;
	protected $_controller;
	protected $_action;
	protected $_template;

	function __construct($model, $controller, $action) {

		$this->_controller = $controller;
		$this->_action = $action;
	//	$this->_model = $model;

		$this->_model = new $model;
		
//		$this->_template =&amp; new Template($controller,$action);
                if(is_array($this->helpers)) {
                    foreach($this->helpers as $helpername) {
                    $this->loadHelpers($helpername);
                    }
                }
		//$this->_model = new $model();
//		$this->_template =&amp; new Template($controller,$action);

	}
	function view($filename) {
		global $lang;
		$baseDirApp = "app";
		$tempVars = $this->templateVars;
		$filePath = "app/view/".$filename.".php";
		require_once($filePath);
	}
	function set($name,$value) {
		$this->_template->set($name,$value);
	}

	function __destruct() {
			//$this->_template->render();
	}
	
	function loadHelpers($helpers){
		require_once(FRAMEWORK.DS."helpers".DS.strtolower($helpers).".php");
		
	}
	function useController($controller){
	/*
            require_once(ROOT."/app/models/".strtolower($controller).".php");
            require_once(ROOT."/app/controllers/".strtolower($controller)."controller".".php");
echo			$controller = ucfirst($controller)."Controller";
			return $cmdobj = new $controller;//."Controller()";
//$cmdobj->page("how_it_works");
*/
	}

}