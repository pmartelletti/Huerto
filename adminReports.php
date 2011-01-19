<?php

require_once 'classes/AdminController.php';
require_once 'classes/SessionVars.class.php';
require_once 'classes/DbConfig.class.php';

SessionVars::start();
DbConfig::setup();
//DB_DataObject::debugLevel(5);


if ( !(SessionVars::isUserLogged()) ) header("location: login.php?view=login");

$controller = new AdminController();
$action = $_POST['action'];
if( !isset($action)) $action = $_GET['action'];
$view = $_GET['view'];

if ( isset($action)){
	
	echo $controller->requestAsyncAction($action);
} else if( isset($view)){

	echo $controller->getView($view);

}

// implementar portada y vista del admin, luego de crear los graficos

?>