<?php

require_once 'classes/reportesGraficosController.php';

$controller = new ReportesGraficosController();

$typeof = "motivosAusesncia";

$url = $controller->getTypeOfGraph($typeof);

if ($url){
	
	echo "<img style='margin-left: 200px' src=' $url' />";
		
} else {
	
	echo "<img style='margin-left: 200px' src='images/noDisponible.jpg' />";
	
}

?>


