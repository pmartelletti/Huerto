<?php
//error_reporting(0);

require_once 'classes/SessionVars.class.php';
require_once 'classes/Portada.class.php';
require_once 'classes/MenuView.class.php';
require_once 'classes/ReportesCtrl.php';
require_once 'classes/DbConfig.class.php';

SessionVars::start();
DbConfig::setup();

if ($_GET['action'] == "detalles_parte_rechazado"){
	ReportesController::getPdfParteRechazado($_GET['id_parte']);
	
}


if ( !(SessionVars::isUserLogged()) ) header("location: login.php?view=login");

$controller = new ReportesController();
$action = $_POST['action'];
if( !isset($action)) $action = $_GET['action'];
$view = $_GET['view'];

if ( isset($action)){
	
	// cambiar el llamado, y hacerle un echo
	echo $controller->requestAsyncAction($action);
	
} else {
	$portada =  new Portada();
	
	$header = new MenuView();
	$header->setDisplayOptions();
	
	$portada->header = $header->getDisplay();

        // si es admin, muestro la vista de administrador
        if( SessionVars::getSeccion() == "Administrador" ){

                $portada->content = $controller->getView("admin");
        } else {
                $portada->content = $controller->getView("reportesIndex");
        }
       

	
	
	$portada->footer = "";
	$portada->setDisplayOptions();
	
	echo $portada->getDisplay();
}
?>