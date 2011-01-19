<?php

require_once 'LoginView.class.php';
require_once 'Secciones.php';
require_once 'SessionVars.class.php';

class LoginCtrl {
	
	public function LoginCtrl(){
		
	}
	
	private function loginUser(){
		
		$res = array();
		
		$usuario = DB_DataObject::factory("usuarios");
		$usuario->setFrom($_POST);
		$seccion = DB_DataObject::factory("secciones");
		$seccion->setFrom($_POST);
		
		$usuario->joinAdd($seccion);

		if( $usuario->find() > 0 ) {
			
			$usuario->fetch();
			
			SessionVars::setSeccion($usuario);
			$res['responseCode'] = 0;
			$res['response'] = "Bienvenido al sitio";
		} else {
			$res['responseCode'] = 100;
			$res['response'] = "Nombre de seccion / password incorrecta.";
		}
		
		return json_encode($res);
		
	}
	
	private function getLoginView() {
		$view = new LoginView();
		$view->setDisplayOptions();
		
		return $view->getDisplay();
	}
	
	public function getView($view){
		
		switch ($view){
			case "login":
				
				return $this->getLoginView();
			
			case "default":
				return ;
		}
	}
	
	public function requestAsyncAction($action){

		switch ($action){
			
			case "login":
				
				return $this->loginUser();
				
			case "logout":
				
				return $this->cerrarSesion();
				
			case "default":
				
				return;
		}
	}
	
	private function cerrarSesion(){
		SessionVars::logout();
		
		$res = array("status"=> "La sesion se cerro correctamente.", "code"=>0);
		
		return json_encode($res);
	}
}

?>