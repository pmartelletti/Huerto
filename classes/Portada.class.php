<?php

require_once 'AbstractView.class.php';

class Portada extends AbstractView {
	
	var $header;
	var $content;
	var $footer;
	
	public function Portada(){
		$this->template = "main.html";
	}
	
}

?>