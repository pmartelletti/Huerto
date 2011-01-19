<?php

class jqGrid {
	
	private $table;
	private $query;
	private $fields;
	
	public function jqGrid(){
		
		$table = $_GET['table'];
		$this->table = $table;
		
		// efectuo las acciones especificadas
		$fields = explode(",",$_GET['fieldsCsv']);
		$this->fields = $fields;
		// agrego aca la query, y ver que m�s que m�s agregar para que el m�todo sea generico
		$query = $_GET['query'];
		$this->setQuery($query);
		
	}
	
	public function setQuery($query){
		$this->query = $query;
	}
	
	/*
	public function setField($fields){
		$this->fields = array();
		for($i=0; $i < count($fields); $i++){
			$this->fields[$i] = "get" . $fields[$i];
		}
	}
	*/
	
private function getJsonResults(){
		
		//DB_DataObject::debugLevel(5);


		// variables del jqgrid
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		
		// elementos para la busqueda
		$isSearch = $_GET['_search'];
		
		if(!$sidx) $sidx =1;
		
		// consulta en la base de datos
		$objeto = DB_DataObject::factory($this->table);
		
		$relations = explode(",", $_GET['relations']);
		foreach($relations as $relation){
			if(!(empty($relation)) ){
				$rel_obj = DB_DataObject::factory($relation);
				$objeto->joinAdd($rel_obj);
				$objeto->selectAs($rel_obj, "%s");
			}
		}
		
		// masterfield
		$masterId = $_GET['masterId'];
		if(isset($masterId)){
			$masterField = $_GET['masterField'];
			$objeto->whereAdd("$masterField = '$masterId'");	
		}
		
		if ( $isSearch == "true" ){
			
			// agrego los filtros a la consulta

			foreach($this->fields as $field){
				if( isset($_GET[$field]) ){
					$dato = $_GET[$field];
					$objeto->whereAdd("$field LIKE '%$dato%'", "AND");
				}
			}
		}
		
		$count = $objeto->find();
				
		// 
		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		
		
		// agrego las restrincciones en la consulta
		$objeto->orderBy("$sidx $sord");
		$objeto->limit("$start", "$limit");
		
		
		
		$objeto->find();
		$i = 0;
		while( $objeto->fetch() ){
			// campos
			$idTable = $this->fields[0];
			$responce->rows[$i]['id']= $objeto->$idTable;
    		
			$responce->rows[$i]['cell']=array();
			for($j=0; $j< count($this->fields); $j++){
				// agregar soporte para tablas que son con join
				$method = $this->fields[$j];
				$responce->rows[$i]['cell'][$j] = utf8_encode($objeto->$method); 
			}
    		$i++;
		}
		
		return json_encode($responce);
		
		
	}
	
	private function tableOperation($oper){
		
		$objeto = DB_DataObject::factory($this->table);
		$id = $_POST['id'];
		
		switch($oper){
			
			case "add":
                                foreach($_POST as $key=>$var){
                                    $_POST[$key] = utf8_decode($var);
                                }
				$objeto->setFrom($_POST);
				$objeto->insert();
				
				break;
				
			case "edit":
				
				$objeto->get($id);
                                foreach($_POST as $key=>$var){
                                    $_POST[$key] = utf8_decode($var);
                                }
				$objeto->setFrom($_POST);
				$objeto->update();
				
				break;
			
			case "del":
				$objeto->get($id);
				$objeto->delete();		
				
				break;
				
			default:
				
				break;
		}
		
		
	}
	
	/**
	 * Decide que operacion tengo que hacer: filtrar datos en la base, o insertar/modificar, etc
	 * @return unknown_type
	 */
	public function gridController(){
		
		$operation = $_POST['oper'];
		
		if ( isset($operation) ){
			// TODO: insertar, modificar, eliminar
			$this->tableOperation($operation);
		} else {
			// consulta en la base de datos
			echo $this->getJsonResults();
		}
		
	}
	
	
	
	
}


?>