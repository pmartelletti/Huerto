<?php

	include("classes/excelwriter.inc.php");
	require_once 'classes/Docente.php';
	require_once 'classes/DbConfig.class.php';
	
	$excel=new ExcelWriter("docentes.xls");
	
	if($excel==false)	
		echo $excel->error;
		
	$myArr=array("ID","Docente","Cargo","CUIL", "Horas", "Situación de Revista", "Sección");
	$excel->writeLine($myArr);
	
	DbConfig::setup();
	
	$docente = DB_DataObject::factory("docente");
	$docente = new DataObjects_Docente();
	$docente->find();
	
	while ( $docente->fetch() ){
		$docente->getLinks();
		$arr = array($docente->doc_id, $docente->doc_nombre, $docente->doc_cargo, $docente->doc_cuil, $docente->doc_hs, $docente->doc_revista, $docente->doc_seccion->_sec_nombre);
		$excel->writeLine($arr);
		
	}
		
	$excel->close();
	echo "data was written into docentes.xls Successfully.";
?>