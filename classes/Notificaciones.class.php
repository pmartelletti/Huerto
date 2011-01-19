<?php

require_once 'classes/Notificaciones.php';
require_once 'classes/Utils.php';

class NotificacionesQuery extends DataObjects_Notificaciones {
		
	// DEFINIR LAS CONSTANTES DE LA CLASE, ESTATICAS
	static public $tipos = array("A" => "ADMIN", "U" => "USER" );
	static public $motivos = array(
		"ART" => "Se ha producido un accidente en la sección, y el mismo no fue reportado a la ART.",
		"PARTE" => "La sección no ha ingresado el parte diario.",
		"INFORME" => "Se ha enviado, por mail, el informe semana de los partes diarios.",
		"PENDIENTES" => "A la fecha de hoy, hay partes pendientes de aprobación."
	);
	static public $email_responsable = "pmartelletti@gmail.com";
	
	
	public function NotificacionesQuery(){
		// TODO: tiene que hacer algo?
	}
	
	public function setMotivo($id_motivo){
		
		$this->not_motivo = NotificacionesQuery::$motivos[$id_motivo];
		
	}
	
	public function setTipo($id_tipo){
		
		$this->not_tipo = NotificacionesQuery::$tipos[$id_tipo];
		
	}
	
	public function setSecccion($seccion){
		
		$this->not_sec_id = $seccion;
		
	}
	
	public function insertDB(){
		
		return $this->insert();
		
	}

	public static function getInstancia($id){
		
		$object = new NotificacionesQuery();
		if( $object->get($id) > 0 ){
			return $object;
		}
		return NULL;
	} 
	/*
	 * DEFINIR EL FORMATO HTML Y CSS DE LAS NOTIFICACIONES. HACER USO DE LAS CLASES
	 * JQUERYUI PARA AGREGAR LOS EFECTOS DE REDONDEZ Y LOS COLORES A LAS MISMAS
	 */
	
	public function getHtml(){
		
		$this->getLinks();
		
		$html = "<div class='ui-corner-all ui-state-highlight notification' id='idNot_". $this->not_id ."'>
			<div class='close-notification ui-icon ui-icon-closethick' id='idElim_" . $this->not_id ."' title='Eliminar Notificacion' ></div>
			<p><b>" . Utils::CambiaFormatoFecha($this->not_fecha_creacion) .", " . $this->_not_sec_id->sec_nombre . " :</b> " . utf8_encode($this->not_motivo) ." </p>
		</div>";
		
		return $html;
		
	}
	
	public function getPlainText(){
		
		$text = $this->_not_sec_id->sec_nombre . ": " . utf8_encode($this->not_motivo);
		
		return $text;
		
	}
	
	public static function getDestinatarioEmail(){
		
	}
	
	static private function getMotivosNotificaciones(){
		// ACA VER SI LE PASO EL ID DEL MOTIVO, Y LO DEVUELVO, 
		// O SI SIEMPRE DEVUELVO EL ARRAY DE MOTIVOS
	}
	
	
	/*
	 * GET NOTIFACIONES ESTATICAS. OBTENGO UN ARRAY DE INFORMACION EN UN FORMATO DADO
	 * PARA PODER CREAR LAS NOTIFICACIONES CON LOS DATOS OBTENIDOS MEDIANTE
	 * ESTOS METODOS.
	 */
	
	static private function getNotificacionesArt(){
		
	}
	
	static public function getNotificacionesSecciones(){
		
	}
	
	static public function getNotificacionesSoporteTecnico(){
		
	}	
	
}


?>