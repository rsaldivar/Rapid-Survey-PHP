<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__))."/src"); // mention the name of the directory in which you install these files.

define('FRAMEWORK', dirname(dirname(__FILE__))."/src/framework/"); // change the framework path here

$url = $_GET['url'];

require_once (ROOT .DS . 'lib' . DS . 'bootstrap.php');