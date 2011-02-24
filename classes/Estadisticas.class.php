<?php require_once 'classes/Secciones.php';require_once 'classes/DbConfig.class.php';require_once 'classes/gChart.php';class Estadisticas {	/*	 * Este atributo contiene un array con los gr�ficos disponibles para el PHP. Lo tengo que setear con PHP y, 	 * al hacer click, buscar en algun controlador, los subdatos de �ste para poder completar los dropdown menues	 * para continuar con la consulta de gr�ficos. Llenar todos los formularios y habilitar el boton de buscar (o	 * ya estaba habilitado, es m�s o menos lo mismo).	 */	private $graficosDisponibles;		private $tipo;	private $subtipo;	private $subgrafico;	private $opcionales;	private $filtro;	private $fecha_from;	private $fecha_to;	public function Estadisticas(){				DbConfig::setup();		$this->setGraphicsOptions();					}		private function setGraphicsOptions(){				// incluir, en un array, la lista de secciones		$secciones = "";		$sec = DB_DataObject::factory("secciones");		$sec->whereAdd("sec_nombre != 'Administrador'");		$num_res = $sec->find();		for($i=0; $i< $num_res; $i++){			$sec->fetch();			$secciones .= $sec->sec_id . ":" . utf8_encode($sec->sec_nombre);			if($i != $num_res - 1) $secciones .= ",";		}			$ausencias = array(			"subgrafico" => 				"re_motivo:Motivos,par_fecha:Cantidad por dia,re_doc_id:Cantidad por persona",			"opcional" => "",			"filtrar" => $secciones,		);				$licencias = array(			"subgrafico" => 				"re_motivo:Motivos,par_fecha:Cantidad por dia,re_doc_id:Cantidad por persona",			"opcional" => "",			"filtrar" => $secciones		);				$tardes = array(			"subgrafico" => 				"re_motivo:Motivos,par_fecha:Cantidad por dia,re_doc_id:Cantidad por persona",			"opcional" => "",			"filtrar" => $secciones		);							$accidentes = array(			"subgrafico" => 				"par_fecha:Cantidad por dia,acc_doc_id:Cantidad por persona",			"opcional" =>				"Alumno:Alumno,Profesor:Docentes",			"filtrar" => $secciones		);				$horasExtras = array(			"subgrafico" => 				"par_fecha:Cantidad por dia,he_doc_id:Cantidad por persona",			"opcional" => "",			"filtrar" => $secciones		);				$this->graficosDisponibles = array(			"Inasistencia" => array("nombre" => "Inasistencia", "datos" => json_encode($ausencias)),			"Licencia" => array("nombre" => "Licencia" , "datos" => json_encode($licencias)),			"Tarde" => array("nombre" => "Llegadas Tardes" , "datos" => json_encode($tardes)), 			"accidentes" => array("nombre" => "Accidentes" , "datos" => json_encode($accidentes)),			"horas_extras" => array("nombre" => "Horas Extras", "datos" => json_encode($horasExtras))		);	}		/**	 * Funcion que devuelve un array con las claves de todos los gr�ficos disponibles	 */	public function getGraficosDisponibles(){				$graficos = array();		foreach($this->graficosDisponibles as $grafico=>$info){			$graficos[$grafico] = $this->graficosDisponibles[$grafico]["nombre"];		}				return $graficos;	}		/*	 * Dada una clave de un grafico, devuelve toda la informacion de dicho grafico en formato JSON para poder	 * completar los dropdown correspondientes. 	 */	public function getInformacionGrafico($clave){			return $this->graficosDisponibles[$clave]["datos"];			}		public function setOpciones($opciones){						$this->fecha_from = $opciones['from'];		$this->fecha_to = $opciones['to'];		if( $opciones['gf_nombre'] == "Licencia" or $opciones['gf_nombre'] == "Inasistencia" or $opciones['gf_nombre'] == "Tarde"  ){			$this->tipo = "reportes";			$this->subtipo = $opciones['gf_nombre'];		} else {			$this->tipo = $opciones['gf_nombre'];		}		$this->subgrafico = $opciones['gf_subgrafico'];		$this->opcionales = $opciones['gf_opcionales'];		$this->filtro = $opciones['gf_filtro'];					}		public function getGraficoImage(){		$partes = DB_DataObject::factory("partes");		$partes->whereAdd("par_fecha BETWEEN '". $this->fecha_from . "' AND '". $this->fecha_to . "'");		// total de partes		if( isset($this->filtro) and $this->filtro != "" ){			$partes->whereAdd("par_sec_id = '" . $this->filtro .  "'" );		}				$consulta = DB_DataObject::factory($this->tipo);		if( isset($this->subtipo) and $this->subtipo != "" ){			$consulta->re_tipo = $this->subtipo;		}				if( isset($this->opcionales) and $this->opcionales != "" ){			$consulta->whereAdd("acc_tipo = '" . $this->opcionales ."'");		}		$consulta->joinAdd($partes);		//DB_DataObject::debugLevel(5);		// cuento el total antes de agrupar		$total = $consulta->find();		// agrupo y vuelvo a ejecutar la consulta		$consulta->selectAdd();		$consulta->selectAdd("*, COUNT(*) as con_total");		$consulta->groupBy($this->subgrafico);					$data = array();		$legends = array();		$labels = array(); // el porcentaje				$legend = $this->subgrafico;				if( $consulta->find() < 1 ){						echo "<p>No existen datos en la base de datos para las caracteristicas elegidas. Por favor, intenten nuevamente			cambiando los parametros establecidos.</p>";			exit();					}				if( preg_match("/doc/i", $this->subgrafico) or preg_match("/fecha/i", $this->subgrafico) ){			// es un grafico de barras para personas / docentes			$otros_total = 0;			$valMax = 0;			while( $consulta->fetch() ){				if( ($consulta->con_total / $total) > 0.1 ){					$data[] = $consulta->con_total;					if( $consulta->con_total > $valMax ) $valMax = $consulta->con_total;										if ( preg_match("/doc/i", $this->subgrafico) ){						$consulta->getLinks();						$rel = "_" . $this->subgrafico;						$doc = $consulta->$rel->doc_nombre;						array_unshift($labels, $doc);					} else {						$rel = $this->subgrafico;						array_unshift($labels, date("d-m-Y", strtotime($consulta->$rel)) );					}														} else {					$otros_total += $consulta->con_total;					if( $otros_total > $valMax ) $valMax = $otros_total;				}							}						if( $otros_total > 0 ){				$data[] = $otros_total;				array_unshift($labels, "Otros");			}			$alto_grafico = 35*sizeof($data) + 40;			$chart = new gBarChart(600, $alto_grafico ,'s', 'h');			$chart->addDataSet($data);			$chart->setVisibleAxes(array("x", "y"));			// el rango es para el m�ximo 			$chart->setDataRange(0,$valMax);			$chart->addAxisRange(0, 0, $valMax);			// agrego el nombre de los docentes			$chart->addAxisLabel(1, $labels );			// el grid para cada valor, por cada valor individual			$porcentaje = (100 / $valMax);			$chart->setGridLines($porcentaje,10);			$chart->setTitle("Partes por secciones");					} else {			while( $consulta->fetch() ){				$data[] = $consulta->con_total;				$legends[] = utf8_encode($consulta->$legend);				$labels[] = number_format(($consulta->con_total / $total * 100), "2") . "% (" . $consulta->con_total . ")"; 			}						$chart = new gPieChart(600, 400);			$chart->addDataSet($data);			$chart->setLegend($legends);			$chart->setLabels($labels);			$chart->setLegendPosition("b");		}				$chart->setTitle($_POST['gf_titulo']); // hacerlo dinamico				echo "<img style='margin-left: 120px; margin-bottom:30px' src='" . $chart->getUrl() ."' />";					}	}?>