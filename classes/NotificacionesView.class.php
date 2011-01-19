<?php

require_once 'AbstractView.class.php';
require_once 'Secciones.php';
require_once 'NotificacionesController.php';


class NotificacionesView extends AbstractView {
	
	private $notifications = array();
	private $controller;
	
	public function NotificacionesView(){
		$this->template = "notificaciones.html";
		$this->controller = new NotificacionesController();
	}
	
	public function setAdminDisplay(){
		// Busco las notificaciones que sean de administrador
		$this->notifications = $this->controller->getNotificacionesAdmin();	
		
	}
	
	public function setUserDisplay(){
		// relleno el array de notifcaciones
		$this->notifications = $this->controller->getNotificacionesUser();
	}
	
	public function getNotificationsDisplay(){
		
		if ( empty( $this->notifications ) ){
			
			echo "<div class='success'>No tiene ninguna notificacion del sistema.</div>";
			return;			
		}
				
		foreach ($this->notifications as $not){
			echo $not->getHtml();
		}		
		
	}
	
}

?>