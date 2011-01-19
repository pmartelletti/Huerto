<?php

// ver si puedo unificar de hacer la vista previa y la vista de detalles en la misma clase y con el mismo template
// lo que habria que cambiar seria el source de la informacion: uno de la BD y otro de un formulario
require_once 'HTML/Template/Flexy/Element.php';

class ReportsDetailView extends AbstractView {
	
	private $instancia;
	var $partes;
	var $accidentes;
	var $horasExtras;
	
	public function ReportsDetailView(){
		
	}
	
	public function setDisplayOptions($id=null){
		$this->template = "parteDetail.html";
		// decidir cual de los "fuentes de informacion" tiene que ser
		if( isset($id) ){
			// saco la informacion de la BD
			$this->instancia = DB_DataObject::factory("partes");
			$this->instancia->get($id); 
			
			// partes
			$rep = DB_DataObject::factory("reportes");
			$rep->re_par_id = $this->instancia->par_id;
			$this->partes = array();
			$rep->find(); 
			
			while( $rep->fetch()){
				$rep->getLinks();
				$this->partes[] = clone($rep);
			}
			
			
			// accidentes 
			$acc = DB_DataObject::factory("accidentes");
			$acc->acc_par_id = $this->instancia->par_id;
			$this->accidentes = array();
			$acc->find(); 
			
			while( $acc->fetch()){
				$acc->getLinks();
				$this->accidentes[] = clone($acc);
			}
			
			// 	horas extras 
			$he = DB_DataObject::factory("horas_extras");
			$he->he_par_id = $this->instancia->par_id;
			$this->horasExtras = array();
			$he->find(); 
			
			while( $he->fetch()){
				
				$he->getLinks();
				$this->horasExtras[] = clone($he);
			}
			
		} else {
			// seteo el parte con la informacion del POST
			$this->instancia = DB_DataObject::factory("partes");
			$this->instancia->setFrom($_GET);
			
			// partes
			$this->partes = array();
			if( isset( $_GET['re_actual'] ) ){
				foreach ($_GET['re_actual'] as $i){
					$parte = DB_DataObject::factory("reportes");
					$parte->re_doc_id = $_GET['re_doc_id'][$i];
					$parte->re_horas = $_GET['re_horas'][$i];
					$parte->re_motivo = $_GET['re_motivo'][$i];
					$parte->re_observacion = utf8_decode($_GET['re_observacion'][$i]);
					$parte->re_tipo = $_GET['re_tipo'][$i];
					
					$this->partes[] = clone($parte);
				}	
			}
			
			// accidentes
			$this->accidentes = array();
			if( isset($_GET['acc_actual'])){
				foreach ($_GET['acc_actual'] as $i){
					$acc = DB_DataObject::factory("accidentes");
					$acc->acc_descripcion = utf8_decode($_GET['acc_descripcion'][$i]);
					$acc->acc_doc_id = $_GET['acc_doc_id'][$i];
					$acc->acc_observacion = $_GET['acc_observacion'][$i];
					$acc->acc_tipo = $_GET['acc_tipo'][$i];
					
					$this->accidentes[] = clone($acc);
				}	
			}
			
			// horas extras
			$this->horasExtras = array();
			if (isset($_GET['he_actual'])){
				foreach ($_GET['he_actual'] as $i){
					$he = DB_DataObject::factory("horas_extras");
					$he->he_cantidad_horas = $_GET['he_cantidad_horas'][$i];
					$he->he_descripcion = utf8_decode($_GET['he_descripcion'][$i]);
					$he->he_doc_id = $_GET['he_doc_id'][$i];
					$he->he_observacion = $_GET['he_observacion'][$i];
					
					$this->horasExtras[] = clone($he);
				}	
			}
		}
		
		$this->setElements();
		
	}
	
	private function setElements(){
		
		$this->instancia->getLinks();
		
		// elementos del parte
		$this->elements['par_id'] = new HTML_Template_Flexy_Element;
		$this->elements['par_id']->setValue($this->instancia->par_id);
		
		$this->elements['par_fecha'] = new HTML_Template_Flexy_Element;
		$this->elements['par_fecha']->setValue($this->instancia->par_fecha);
		
		$this->elements['par_sec_id'] = new HTML_Template_Flexy_Element;
		$this->elements['par_sec_id']->setValue($this->instancia->_par_sec_id->sec_nombre);
		
		$this->elements['par_us_id'] = new HTML_Template_Flexy_Element;
		$this->elements['par_us_id']->setValue($this->instancia->_par_us_id->us_nombre);
		
		$this->elements['par_observaciones'] = new HTML_Template_Flexy_Element;
		$this->elements['par_observaciones']->setValue(utf8_encode($this->instancia->par_observaciones));
		
	}
	
	public function getDBproperty($p_object, $p_property){
		
		$p_object->getLinks();

		$methods = explode(".",$p_property);
		$value = $p_object;
		foreach($methods as $method){
			$value = $value->$method;
		}
		
		return utf8_encode($value);
	}
	
	// para el caso particular de que sea un accidente
	public function getNombreAccidente($p_acc){
		
		if( $p_acc->acc_tipo == "Alumno" ){
			//
			$nombre =  $p_acc->getLink("acc_doc_id", "alumnos","alu_id");
			return utf8_encode($nombre->alu_nombre);
		} else {
			$p_acc->getLinks();
			$nombre = $p_acc->_acc_doc_id;
			return utf8_encode($nombre->doc_nombre);
		}
		
	}
	
	
	
}

?>