<?php
ini_set('display_errors','1');

require ('classes/gChart.php');
require_once 'classes/Partes.php';
require_once 'classes/DbConfig.class.php';

DbConfig::setup();

//DB_DataObject::debugLevel(5);

$partes = DB_DataObject::factory("partes");
// total de partes
$total = $partes->count();
// consulta agrupada
$partes->selectAdd();
$partes->selectAdd("*, COUNT(*) as par_total");
$partes->groupBy("par_sec_id");
$partes->find();



$data = array();
$legends = array();
$labels = array(); // el porcentaje

while( $partes->fetch() ){
	$partes->getLinks();
	$data[] = $partes->par_total;
	$legends[] = utf8_encode($partes->_par_sec_id->sec_nombre);
	$labels[] = number_format(($partes->par_total / $total * 100), "2") . "% (" . $partes->par_total . ")"; 
}

$piChart = new gPieChart(500,350);
$piChart->addDataSet($data);
$piChart->setLegend($legends);
$piChart->setLabels($labels);
$piChart->setTitle("Partes por secciones");
$piChart->setLegendPosition("b");

?>
<img style="margin-left: 200px" src="<?php print $piChart->getUrl();  ?>" />
