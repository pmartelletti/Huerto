<?php

require_once 'DB/DataObject.php';
require_once 'classes/EstadisticasView.class.php';
require_once 'classes/Estadisticas.class.php';

class EstadisticasController {
	
	private $estadisticas;
	
	public function EstadisticasController(){
		$this->estadisticas = new Estadisticas();
	}
	
	public function getView($view=""){
		
		// creo que es la unica vista
		$view = new EstadisticasView();
		$opciones = $this->estadisticas->getGraficosDisponibles();
		array_unshift($opciones, "Elija un grafico");
		$view->setDisplayOptions( $opciones );
		return $view->getDisplay();
		
		
	}
	
	public function requestAsyncAction($action){
		
		switch ($action){
			
			case "getOpcionesGraficos":
				return $this->getOpcionesGraficos();
				
			case "getGrafico":
				
				return $this->getGrafico();
				
			default:
				return;
			
		}
		
	}
	
	private function getGrafico(){
		
		// asigno el POST a las propiedades del grafico
		$this->estadisticas->setOpciones($_POST);
		// devuelvo el grafico instancia
		print $this->estadisticas->getGraficoImage();
		
		return;
	}
	
	private function getOpcionesGraficos(){
		
		$optionVal = $_POST['gf_nombre'];
		
		return $this->estadisticas->getInformacionGrafico($optionVal);
		
	}
	
	/*
	 * METODOS PARA CREAR LOS GRAFICOS Y LOS INFORMES SEMANALES
	 */
	
	
	
}


?>