<?php

require 'DB/DataObject.php';
require 'gChart.php';
require_once 'DbConfig.class.php';

class ReportesGraficosController {
	
	public function ReportesGraficosController(){
		DbConfig::setup();
	}
	
	public function getTypeOfGraph($type){
		
		switch ($type){
			
			case "motivosAusencia":
				
				return $this->getMotivosAusenciaGraph();
				
			default:
				
				return;
			
		}
	}
	
	private function getMotivosAusenciaGraph(){
		
		$motivos = DB_DataObject::factory("reportes");
		$total = $motivos->count();
		// consulta agrupada
		$motivos->selectAdd();
		$motivos->selectAdd("COUNT(*) AS re_total, re_motivo");
		$motivos->groupBy("re_motivo");
		$motivos->find();
		
		$data = array();
		$legends = array();
		$labels = array(); // el porcentaje
		
		while( $motivos->fetch() ){
			//$motivos->getLinks();
			$data[] = $motivos->re_total;
			$legends[] = utf8_encode($motivos->re_motivo);
			$percent = number_format(($motivos->re_total / $total * 100), "2") . "%";
			$labels[] = utf8_encode($motivos->re_motivo) . "( $percent )";
		}
		
		$piChart = new gPieChart(700,400);
		$piChart->addDataSet($data);
		$piChart->setLegend($legends);
		$piChart->setLabels($labels);
		$piChart->setTitle("Motivos de los partes");
		$piChart->setLegendPosition("b");
		
		return $piChart->getUrl();
		
		
		
		
	}
	
	
}