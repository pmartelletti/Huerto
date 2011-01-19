<?php

require_once 'AbstractView.class.php';
require_once 'Secciones.php';


class ReportesIndexView extends AbstractView {
	
	
	var $seccionName;
	
	/**
	 * Constructor
	 * @return unknown_type
	 */
	public function ReportesIndexView(){
		$this->template = "reportesTab.html";
	}
	
	public function setDisplayOptions(){
		// manejo si el usuario es admin
		$this->seccionName = SessionVars::getSeccion();
	}
	
}

?>