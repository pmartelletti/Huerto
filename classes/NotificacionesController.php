<?php

require_once 'Notificaciones.class.php';
require_once 'NotificacionesView.class.php';
require_once 'SessionVars.class.php';

class NotificacionesController {


	public function getView($view){
		
		
		switch ($view){
			
			case "notificacionesUsuario":
				
				return $this->getNotificacionesUsuariosView();
				
			case "notificacionesAdmin":
				
				return $this->getNotificacionesAdminView();
			
			default:
				return;
		}
	}
	
	private function getNotificacionesAdminView(){
		
		$view = new NotificacionesView();
		$view->setAdminDisplay();
		return $view->getDisplay();
				
	}
	
	private function getNotificacionesUsuariosView(){
		
		$view = new NotificacionesView();
		$view->setUserDisplay();
		return $view->getDisplay();
		
	}
	
	
	
	public function requestAsyncAction($action){
		
		
		switch ($action){
			
			case "crearNotificacionesDiarias":
			
				return $this->crearNotificacionesDiarias();

			case "deleteNotificacion":
				
				if( $this->deleteNotificacion() ){
					return json_encode(array("statusCode" => 100, "statusMsg" => "La notificacion se ha eliminado correctamente"));
				} else {
					return json_encode(array("statusCode" => 200, "statusMsg" => "Hubo un error al eliminar la notificacion"));
				}
			
			
			default:
				return json_encode(array("error"=>"El mensaje ha fallado"));
		}
		
	}
	
	private function deleteNotificacion(){
		
		$id = $_POST['idNot'];
		$not = NotificacionesQuery::getInstancia( $id );
		$not->not_mostrar = 0;
		$not->not_fecha_modificacion = date("Y-m-d");
		
		return $not->update();
		
	}
	
	private function crearNotificacionesDiarias(){
		
		// partes no ingresados
		$secciones = DB_DataObject::factory("secciones");
		$secciones->find();
		while( $secciones->fetch() ){
			// ingreso los pares de las secciones distintas del administrador
			if($secciones->sec_login != "admin"){
				// partes no ingresados
				$this->notificacionParteNoingresado($secciones->sec_id, "A");
				$this->notificacionParteNoingresado($secciones->sec_id, "U");
				// ART no reportado
				$this->notificacionArtNoReportado($secciones->sec_id, "U");
				$this->notificacionArtNoReportado($secciones->sec_id, "A");
			}
		}
		
		// partes pendientes de aprobacion
		$this->notificacionPartesPendientesAprobacion(5, "A");
	}
	
	private function notificacionArtNoReportado($sec_id, $not_tipo_id){
		// muestro los reporte ART , por mas que sean de alumnos
		$date = date("Y-m-d", strtotime("-1 day"));
		$parte = DB_DataObject::factory("partes");
		$parte->par_fecha = $date;
		$parte->par_sec_id = $sec_id;
		
		$art = DB_DataObject::factory("accidentes");
		$art->acc_reporte_art = 0; // el accidente no est� reportado a ART
		$art->joinAdd($parte);
		if( $art->find(true) > 0 ){
			// hay accidentes no reportados a la ART
			// creo notificaciones
			$notificacion = new NotificacionesQuery();
			$notificacion->setSecccion($sec_id);
			$notificacion->setMotivo("ART");
			$notificacion->setTipo($not_tipo_id);
			$notificacion->not_fecha_creacion = date("Y-m-d");
			
			// inserto en la base de datos
			
			return $notificacion->insertDB();
		}
		
	}
	
	private function notificacionPartesPendientesAprobacion($sec_id, $not_tipo_id){
		
		$partes = DB_DataObject::factory("partes");
		$partes->par_aprobado = 0;
		$fecha_limite = date("Y-m-d", strtotime("-7 days"));
		$partes->whereAdd("par_fecha < '$fecha_limite'");
		if( $partes->find() > 0 ){
			// tiene partes pendientes de aprobacion
			// creo una notificacion con fecha de hoy
			$notificacion = new NotificacionesQuery();
			$notificacion->setSecccion($sec_id);
			$notificacion->setMotivo("PENDIENTES");
			$notificacion->setTipo($not_tipo_id);
			$notificacion->not_fecha_creacion = date("Y-m-d");
			
			// inserto en la base de datos
			
			return $notificacion->insertDB();
			
		}
		
	}
	
	private function notificacionParteNoingresado($sec_id, $not_tipo_id){
		
		// busco si ingres� parte en el d�a de ayer
		$date = date("Y-m-d", strtotime("-1 day"));
		$parte = DB_DataObject::factory("partes");
		$parte->par_fecha = $date;
		$parte->par_sec_id = $sec_id;
		
		if( !($parte->find() > 0) ){
			// no ingreso un parte el dia anterior
			// creo una notificacion con fecha de hoy
			$notificacion = new NotificacionesQuery();
			$notificacion->setSecccion($sec_id);
			$notificacion->setMotivo("PARTE");
			$notificacion->setTipo($not_tipo_id);
			$notificacion->not_fecha_creacion = date("Y-m-d");
			
			// inserto en la base de datos
			
			return $notificacion->insertDB();
		}
		
		return false;
		
	}
	
	public function getNotificacionesAdmin(){
		
		$not = new NotificacionesQuery();
		$not->not_tipo = NotificacionesQuery::$tipos["A"];
		$not->not_mostrar = 1;
		$not->find();
		
		$res = array();
		
		while( $not->fetch() ){
			$res[] = clone( $not );
		}
		
		return $res;
		
	}
	
	public function getNotificacionesUser(){
		
		$not = new NotificacionesQuery();
		$not->not_tipo = NotificacionesQuery::$tipos["U"];
		$not->not_mostrar = 1;
		
		$sec = DB_DataObject::factory("secciones");
		$sec->sec_nombre = SessionVars::getSeccion(); 
		$sec->find(); $sec->fetch();
		
		$not->not_sec_id = $sec->sec_id;
		$not->find();
		
		$res = array();
		
		while( $not->fetch() ){
			$res[] = clone( $not );
		}
		
		return $res;
		
	}
	
}