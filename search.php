<?php

require_once 'classes/Docente.php';
require_once 'classes/DbConfig.class.php';

DbConfig::setup();

$busqueda = $_GET['term'];


$q = strtolower($busqueda);

$docente = new DataObjects_Docente();
$docente->query("SELECT * FROM {$docente->__table} WHERE doc_nombre LIKE '%$busqueda%' ORDER BY doc_nombre");

$res = array();

while ( $docente->fetch() ){
	
	$res[] = array("label" => $docente->getdoc_nombre(), "id" => $docente->getdoc_id());
	
}


echo json_encode($res);


?>