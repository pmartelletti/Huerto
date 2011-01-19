<?php

require_once 'classes/NotificacionesController.php';
require_once 'classes/SessionVars.class.php';
require_once 'classes/DbConfig.class.php';

SessionVars::start();
DbConfig::setup();


if ( !(SessionVars::isUserLogged()) ) header("location: login.php?view=login");

$controller = new NotificacionesController();
$action = $_POST['action'];
if( !isset($action)) $action = $_GET['action'];
$view = $_GET['view'];

if ( isset($action)){
	
	echo $controller->requestAsyncAction($action);
} else if( isset($view)){

	echo $controller->getView($view);

}

?>