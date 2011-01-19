<?php

error_reporting(0);

require_once 'classes/Portada.class.php';
require_once 'classes/MenuView.class.php';
require_once 'classes/LoginCtrl.php';
require_once 'classes/DbConfig.class.php';

DbConfig::setup();
SessionVars::start();

$action = $_POST['action'];
$view = $_GET['view'];

$controller = new LoginCtrl();

if (isset($action)){
	
	echo $controller->requestAsyncAction($action);
	
} else if ( isset($view) ){
	
	$portada = new Portada();
	
	$header = new MenuView();
	$header->setDisplayOptions();
	
	$portada->header = $header->getDisplay();
	$portada->content = $controller->getView($view);
	$portada->setDisplayOptions();
	
	echo $portada->getDisplay();
	
}


?>
