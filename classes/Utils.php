<?php


class Utils {
	
	static function CambiaFormatoFecha($fecha){
		
   		list($anio,$mes,$dia) = explode("-",$fecha); 
    
   		return $dia."-".$mes."-".$anio; 	
		
	}
	
}