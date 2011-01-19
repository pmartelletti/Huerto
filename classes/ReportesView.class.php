<?php

require_once 'AbstractView.class.php';
require_once 'Secciones.php';
require_once 'DB/DataObject.php';
require_once 'HTML/Template/Flexy/Element.php';


class ReportesView extends AbstractView {
	
	private $instancia;
	var $seccion;
	var $seccion_id;
	var $us_nombre;
	
	/**
	 * Constructor
	 * @return unknown_type
	 */
	public function ReportesView(){
		$this->template = "reportes.html";
		$this->instancia = $seccion ;
	}
	
	public function setDisplayOptions($sec_nombre, $us_nombre){
		
		$this->seccion = $sec_nombre;
		$this->us_nombre = $us_nombre;
		
		// busco la seccion
		$seccion = DB_DataObject::factory("secciones");
		$seccion->sec_nombre = $sec_nombre;
		$seccion->find(); $seccion->fetch();
		
		$this->seccion_id = $seccion->sec_id;
		
		$this->setOptions();
	}
	
	private function setOptions(){
		
		// secretaria
		$secretaria = DB_DataObject::factory("usuarios");
		$secretaria->us_nombre = $this->us_nombre;
		$secretaria->find(); $secretaria->fetch();
		
		$this->elements["par_us_id"] = new HTML_Template_Flexy_Element;
		$this->elements["par_us_id"]->setOptions(array(
			$secretaria->us_id => $secretaria->us_nombre 
		));
		
	}
	
}