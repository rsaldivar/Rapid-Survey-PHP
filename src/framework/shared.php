<?php

/** Check if environment is development and display errors **/
function setReporting() {
if (DEVELOPMENT_ENVIRONMENT == true) {
//	error_reporting(E_ALL);
	//ini_set('display_errors','On');
} else {
	error_reporting(E_ALL);
	ini_set('display_errors','Off');
	ini_set('log_errors', 'On');
	ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
}
}

/** Check for Magic Quotes and remove them **/

function stripSlashesDeep($value) {
	$value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
	return $value;
}

function removeMagicQuotes() {
if ( get_magic_quotes_gpc() ) {
	$_GET    = stripSlashesDeep($_GET   );
	$_POST   = stripSlashesDeep($_POST  );
	$_COOKIE = stripSlashesDeep($_COOKIE);
}
}

/** Check register globals and remove them **/

function unregisterGlobals() {
    if (ini_get('register_globals')) {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value) {
            foreach ($GLOBALS[$value] as $key => $var) {
                if ($var === $GLOBALS[$key]) {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}

/** Main Call Function **/

function callHook() {
	global $url;
	global $router;
	
	$url = $_GET["url"];

	if(REWRITEURL==false) {

	$url = explode('index.php/',$_SERVER["REQUEST_URI"]);

	$url = $url[1];
	}

	$urlArray = array();
	$urlArray = explode("/",$url);

	$controller = $urlArray[0];

	if(count($router) && is_array($router)) {
		foreach($router as $ind=>$val) {
			if($controller==$ind) {
				$controller = $val;
				$action =  $ind;
			}
		}
	}

	if($controller=="") {
		$controller = "home";
	}
	array_shift($urlArray);

	if($action=="") {
		$action = str_replace("-","_",$urlArray[0]);
		if($action=="") {
			$action = "index";
		}
	}
	array_shift($urlArray);
	$queryString = $urlArray;
	$controllerName = $controller;
	$controller = ucwords($controller);
	//$model = rtrim($controller, 's');
	//$model = rtrim($controller, 's');
	$model = $controller;
	$controller .= 'Controller';

	$dispatch = new $controller($model,$controllerName,$action);

	if ((int)method_exists($controller, $action)) {
		call_user_func_array(array($dispatch,$action),$queryString);
	} else {
		/* Error Generation Code Here */
	}
}

/** Autoload any classes that are required **/

function __autoload($className) {
	global $baseDirApp;
	if (file_exists(ROOT . DS . 'lib' . DS . strtolower($className) . '.class.php')) {
		require_once(ROOT . DS . 'lib' . DS . strtolower($className) . '.class.php');
	} else if (file_exists(ROOT . DS . 'app' . DS . 'controllers' . DS . strtolower($className) . '.php')) {
		require_once(ROOT . DS . 'app' . DS . 'controllers' . DS . strtolower($className) . '.php');
	} else if (file_exists(ROOT . DS . 'app' . DS . 'models' . DS . strtolower($className) . '.php')) {
		require_once(ROOT . DS . 'app' . DS . 'models' . DS . strtolower($className) . '.php');
	} else {
		/* Error Generation Code Here */
		echo "Error in loading class -> ".$className;		
		exit();
	}
}

setReporting();
removeMagicQuotes();
unregisterGlobals();
callHook();