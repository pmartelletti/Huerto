<?php

require_once 'classes/EstadisticasController.class.php';
require_once 'classes/SessionVars.class.php';

SessionVars::start();

// verificar que se trate del administrador!
if( SessionVars::getSeccion() == "Administrador"){
	
	$action = $_POST['action'];
	if(!isset($action)) $action = $_GET['action'];
	$controller = new EstadisticasController();
	
	
	if( isset($action)){
		// acciones del script
		echo $controller->requestAsyncAction($action);
	} else {
		
		echo $controller->getView();
	}
	
	
	
}

?>