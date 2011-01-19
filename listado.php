<?php

require_once 'classes/Reportes.php';
require_once 'classes/DbConfig.class.php';

DbConfig::setup();

//DB_DataObject::debugLevel(5);

// variables del jqgrid
$page = 1; //$_GET['page']; // get the requested page
$limit = 10; //$_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = "ASC" ; //$_GET['sord']; // get the direction
if(!$sidx) $sidx =1;

$sField = "re_id"; //$_GET['searchField'];
$sValue =  "3"; //$_GET['searchString'];
$sOper = $_GET['searchOper'];

// consulta en la base de datos
$reportes = new DataObjects_Reportes();
$count = $reportes->find();

// 
if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} else {
	$total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;

// agrego las restrincciones en la consulta
if(isset($sField)) $reportes->whereAdd("$sField LIKE '%$sValue%'");
$reportes->orderBy("$sidx $sord");
$reportes->limit($start, $limit);

$reportes->find();
$i = 0;
while( $reportes->fetch() ){
	$reportes->getLinks();
	$responce->rows[$i]['id']=$reportes->getre_id();
    $responce->rows[$i]['cell']=array($reportes->getre_id(), $reportes->_re_doc_id->doc_nombre, $reportes->getre_fecha_ingreso(), $reportes->_re_sec_id->sec_nombre, $reportes->getre_tip_id(), $reportes->getre_horas(),$reportes->getre_mot_id());
    $i++;
}

echo json_encode($responce);


?>