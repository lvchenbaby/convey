<?php
$config=include "config.php";
include "functions.php";
include "mysql.php";
include ROOT."/controllers/Controller.php";
$dbconf=$config['dbconfig'];
$dbcon=connectMysql($dbconf);
$route=getRoute();
ob_start();
execAction($route);
$output=ob_flush();

