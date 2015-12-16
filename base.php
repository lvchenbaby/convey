<?php
$config=include "config.php";
include "functions.php";
include "mysql.php";

define("CSS","/view/css");
define("JS","/view/js");
define("IMAGE","/view/image");

include ROOT."/controllers/Controller.php";
$dbconf=$config['dbconfig'];
$dbcon=connectMysql($dbconf);
$route=getRoute();
ob_start();
execAction($route);
$output=ob_flush();

