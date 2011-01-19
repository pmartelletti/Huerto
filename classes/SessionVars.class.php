<?php

class SessionVars {
	
	public function SessionVars(){
		
	}
	
	public static function start(){
		session_start();
	} 
	
	public static function setSeccion($seccion){
		$_SESSION['seccion'] = $seccion->sec_nombre;
		$_SESSION['usuario'] = $seccion->us_nombre;
	}
	
	public static function getSeccion(){
		return $_SESSION['seccion'];
	}
	
	public static function getUsuario(){
		return $_SESSION['usuario'];
	}
	
	public static function isUserLogged(){
		return isset($_SESSION['seccion']);
	}
	
	public static function logout(){
		session_destroy();
	}
	
}

?>