<?php

require_once 'AbstractView.class.php';
require_once 'Secciones.php';


class MenuView extends AbstractView {
	
	/**
	 * Constructor
	 * @return unknown_type
	 */
	public function MenuView(){
		$this->template = "menu.html";
	}
	
	public function setDisplayOptions(){
		// manejo si el usuario es admin
	}
	
}