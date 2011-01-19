<?php

require_once 'DB/DataObject.php';
require_once 'classes/DbConfig.class.php';

DbConfig::setup();

/*                     			 h.m.s.mes.dia.año*/ 
$fechaInicial			= mktime(0,0,0,1 ,1 ,2011);
$fechaFin = mktime(0,0,0,12,31,2011);
// array de los meses
$meses = array();
$feriados = array("1-1", "7-3", "8-3", "24-3", "25-3", "2-4", "22-4", "1-5", "20-6", "9-7", "8-12", "9-12", "25-12", "15-12", "10-10", "28-11", "21-4", "24-4");

$i = 0;

while ($fechaInicial < $fechaFin ){
	
	// mientras tenga días en el año, proceso los días
	$diaSemana = getdate($fechaInicial);
	if($diaSemana["wday"]!=0 && $diaSemana["wday"]!=6)
	{	$feriado	= $diaSemana['mday']."-".$diaSemana['mon'];
		if(!in_array($feriado,$feriados)){
			$mes = $diaSemana["mon"];
			$meses[$mes] +=1;
		}
	}
	
	$fechaInicial = mktime(0,0,0,$diaSemana["mon"], $diaSemana["mday"] + 1, $diaSemana["year"]);
		
}
echo "<pre>";
print_r($meses);
echo "</pre>";

foreach($meses as $mes=>$cantidad){
	$fechas = DB_DataObject::factory("dias_habiles_mes");
	$fechas->dhm_mes = $mes;
	$fechas->dhm_cantidad_dias = $cantidad;

//	echo $fechas->insert();
}


?>
