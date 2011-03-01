<?php

require_once 'Reportes.php';
require_once 'Accidentes.php';
require_once 'Horas_extras.php';
require_once 'TabsAdminView.class.php';
require_once 'ReportsDetailView.class.php';
require_once 'Jqgrid.class.php';
require_once 'reportePdf.class.php';
require_once 'classes/phpMailer/class.phpmailer.php';
require_once 'classes/Notificaciones.class.php';

class AdminController {


	public function getView($view){
		
		switch ($view){
			case "tabs":
			
				$tabView = $_GET['tabView'];
				return $this->getTabsView($tabView);
			
			case "parteDetail": 
				
				// aca hago el output del formulario, lo muestro en el form
				return $this->getParteDetailView();
				
			default:
				return;
		}
	}
	
	private function getParteDetailView(){
		$idParte = $_GET['idParte'];
		
		$view = new ReportsDetailView();
		$view->setDisplayOptions($idParte);
		return $view->getDisplay();
		
	}
	
	private function getTabsView($tab){
		
		$content = new TabsAdminView();
		$content->setDisplayOptions($tab);
		return $content->getDisplay();
		
	}
	
	public function requestAsyncAction($action){
		
		//  borrar esto, cambiar las fechas
		$fecha_ini = "2010-08-31";
		$fecha_fin = "2010-12-31";
	
		//list($fecha_ini, $fecha_fin) = $this->getFechasBusqueda();
		$filtro = $_POST['re_filtro'];
		
		switch ($action){
			
			case "tableReportes":
				
				return $this->getTablaReportes();
				
			case "jqgridTable":
				
				$jqgrid = new jqGrid();
				// ejecuto el controlador, que decide qu� accion realizar
				//var_dump($jqgrid);
				$jqgrid->gridController();
				
				return;
			
			case "reportesInfo":
				
				// reportes
				$inas =  $this->getReportes($fecha_ini, $fecha_fin, "Inasistencia", $filtro);
				$tarde = $this->getReportes($fecha_ini, $fecha_fin, "Llegada Tarde", $filtro);
				$retiro =  $this->getReportes($fecha_ini, $fecha_fin, "Retiro Anticipado", $filtro);
				
				return json_encode(array($inas, $tarde, $retiro));
			
			case "accidentesInfo":
								
				// accidentes
				$profe = $this->getAccidentes($fecha_ini, $fecha_fin, "Profesor", $filtro);
				$alum = $this->getAccidentes($fecha_ini, $fecha_fin, "Alumno", $filtro);
				
				return json_encode(array($profe,$alum));
				
			case "horasExtrasInfo":
				
				// horas extras
				$horasExtras = $this->getHorasExtras($fecha_ini, $fecha_fin, $filtro);
				
				return json_encode(array($horasExtras));
				
			case "confirmaParte":
				
				$id = $_POST['idParte'];
				
				$parte = DB_DataObject::factory("partes");
				$parte->get($id);
				$parte->par_aprobado = 1;
				
				$usuario = DB_DataObject::factory("usuarios");
				$usuario->us_nombre = SessionVars::getUsuario();
				$usuario->find(true);
				
				$parte->par_us_id_aprobacion = $usuario->us_id; 
				$parte->par_fecha_aprobacion = time();
				
				$res = array();
				
				if ( $parte->update() ){
					
					$res = array("resCode" => "200", "resMsg" => "El parte se ha aprobado correctamente");
					
				} else {
					$res = array("resCode" => "100", "resMsg" => "Hubo un error al aprobar el parte. Intenta nuevamente");
				}
				
				return json_encode($res);

			case "rechazaParte":

				return $this->rechazarParte();
				
			case "corroborarParte":
				return $this->corroborarParte();
				
			case "closeSession":
				
				SessionVars::logout();
				
				return;
				
			case "listarSecciones":
				
				return $this->listarSecciones();

			case "listarZonasAlumnos":

				return $this->listarZonasAlumnos();

			case "listarCargosDocentes":

				return $this->listarCargosDocentes();

			case "informePdf":

				return $this->getInformePdf();
				
			case "getResultadosPdf":
				return $this->getResultadosBusquedaPDF();
			
			default:
				return json_encode(array("error"=>"El mensaje ha fallado"));
		}
		
	}

        private function rechazarParte(){
            $id = $_POST['idParte'];

            $parte = DB_DataObject::factory("partes");
            $parte->get($id);
            $parte->par_aprobado = -1;
            
            $usuario = DB_DataObject::factory("usuarios");
			$usuario->us_nombre = SessionVars::getUsuario();
			$usuario->find(true);
			
			$parte->par_us_id_aprobacion = $usuario->us_id; 
			$parte->par_fecha_aprobacion = time();
            

            $res = array();

            if ( $parte->update() ){
            	
            		// creo una notificacion en la seccion
            		$notificacion = new NotificacionesQuery();
            		$notificacion->setMotivo("RECHAZO");
            		$notificacion->setSecccion($parte->par_sec_id);
            		$notificacion->setTipo("U");
            		$notificacion->insertDB();

                    // envio el email a la seccion correspondiente
                    $seccion = DB_DataObject::factory("secciones");
                    $seccion->get( $parte->par_sec_id);
                    $seccion->find(true);
                    $to = $seccion->sec_email;
                    $from = "noreply@parteshuerto.com.ar";
                    $url_descarga = "http://localhost/huerto/index.php?action=detalles_parte_rechazado&id_parte=" . $parte->par_id;
                    $body = "<p>El parte diario realizado por la seccion ". $seccion->sec_nombre . " el dia " . date("d-m-Y", strtotime($parte->par_fecha)) . " ha sido rechazada. Por favor, vuelva a ingresarla para la correcta adminsitracion de los datos.</p>
                    <p>Puede ver los datos del mismo descargando el <a href='$url_descarga'>siguiente formulario en formato PDF.</a></p>    
                    Atte.,

                        La administracion.";
                    $this->sendMail($from, $to, "Parte Diario Rechazado", $body);

                    // muestro el resultado

                    $res = array("resCode" => "200", "resMsg" => "El parte ha sido rechazado.");

            } else {
                    $res = array("resCode" => "100", "resMsg" => "Hubo un error al rechazar el parte. Intenta nuevamente");
            }

            return json_encode($res);
        }
        
        private function corroborarParte(){
        	$id = $_POST['idParte'];
        	
        	$parte = DB_DataObject::factory("partes");
            $parte->get($id);
            
            // creo una notificacion en la seccion
            $notificacion = new NotificacionesQuery();
            $notificacion->setMotivo("CORROBORACION");
            $notificacion->setSecccion($parte->par_sec_id);
            $notificacion->setTipo("U");
            $notificacion->insertDB();

			// envio el email a la seccion correspondiente
			$seccion = DB_DataObject::factory("secciones");
			$seccion->get( $parte->par_sec_id);
			$seccion->find(true);
			$to = $seccion->sec_email;
			$from = "noreply@parteshuerto.com.ar";
			$url_descarga = "http://localhost/huerto/index.php?action=detalles_parte_rechazado&id_parte=" . $parte->par_id;
			$body = "<p>El parte diario realizado por la seccion ". $seccion->sec_nombre . " el dia " . date("d-m-Y", strtotime($parte->par_fecha)) . " contiene informacion que puede ser considerada invalida. Por favor, revise los datos del mismo y verifique que todos los datos sean correctos y de aviso a algun administrador .</p>
                    <p>Puede ver los datos del mismo descargando el <a href='$url_descarga'>siguiente formulario en formato PDF.</a></p>    
                    Atte.,

                        La administracion.";
			if ( $this->sendMail($from, $to, "Corroborar Parte Diario", $body) ){
				// muestro el resultado
				$res = array("resCode" => "200", "resMsg" => "Se ha enviado un mail a la secretaria para que verifique el parte indicado.");
            } else {
				$res = array("resCode" => "100", "resMsg" => "Hubo un error al rechazar el parte. Intenta nuevamente");
            }
            
            return json_encode($res);
        }


        private function sendMail($from, $to, $subject, $body){

            $mail = new PHPMailer();
            $mail->From     = $from; // Mail de origen
            $mail->FromName = "Soporte Tecnico Huerto"; // Nombre del que envia
            $mail->AddAddress($to); // Mail destino, podemos agregar muchas direcciones
            $mail->AddReplyTo("soporte@parteshuerto.com.ar"); // Mail de respuesta

            $mail->WordWrap = 80; // Largo de las lineas
            $mail->Subject  =  $subject;
            $mail->IsHTML(true); // Podemos incluir tags html

            $mail->Body     =  $body;

            $mail->IsSMTP(); // vamos a conectarnos a un servidor SMTP
            $mail->Host = "mail.parteshuerto.com.ar"; // direccion del servidor
            $mail->SMTPAuth = true; // usaremos autenticacion
            $mail->Username = "noreply@parteshuerto.com.ar"; // usuario
            $mail->Port = 26;
            $mail->Password = "0220404"; // contraseña

            return $mail->send();
        }

        private function getInformePdf(){
            $id = explode("-", $_GET['idParte']);
            // var_dump($id);

            $pdf=new reportePdf($id);
            $pdf->prepareOutput();
            return $pdf->Output();
        }
        
        private function getResultadosBusquedaPDF(){
        	
        	$fecha_ini = $_POST['par_fecha_ini'];
        	$fecha_fin = $_POST['par_fecha_fin'];
        	$estado = $_POST['par_aprobado'];
        	$seccion = $_POST['par_sec_id']; 
        	
        	$partes = DB_DataObject::factory("partes");
        	
        	if( $fecha_ini != "" or $fecha_fin != "" ){
        		if ($fecha_ini != "" and $fecha_fin != "") {
        			$partes->whereAdd("par_fecha BETWEEN '$fecha_ini' AND '$fecha_fin'");
        		} else {
        			return json_encode(array("statusCode"=> "100", "statusMsg"=>"Debe indicar una fecha de inicio y de fin."));
        		}
        	}
        	
        	if($estado != "" ) $partes->whereAdd("par_aprobado = '$estado'");
        	
        	if( $seccion != "" ) $partes->whereAdd("par_sec_id = '$seccion'");
        	
        	// DB_DataObject::debugLevel(5);
        	
        	$partes->find();
        	$res = array();
        	while( $partes->fetch() ){
        		$partes->getLinks();
        		$estado = "";
        		if($partes->par_aprobado == -1) $estado = "Rechazado";
        		else if( $partes->par_aprobado == 0 ) $estado = "Pendiente";
        		else $estado = "Aprobado";
        		$res[] = array("par_id" => $partes->par_id, "par_secccion" => $partes->_par_sec_id->sec_nombre, "par_fecha" => date("d-m-y", strtotime($partes->par_fecha)), "par_estado" => $estado);
        	}
        	
        	return json_encode(array("statusCode" => "0" , "statusMsg" => "OK", "data" => $res));
        	
        }

    
	private function listarSecciones(){
		
		$busqueda = $_GET['term'];
		$q = strtolower($busqueda);

		$seccion = DB_DataObject::factory("secciones");
		$seccion->query("SELECT * FROM {$seccion->__table} WHERE sec_nombre LIKE '%$busqueda%' ORDER BY sec_nombre");
		$res = array();

		while ( $seccion->fetch() ){
			$res[] = array("label" => utf8_encode($seccion->sec_nombre), "id" => $seccion->sec_id);
		}

		return json_encode($res);
	}

        private function listarZonasAlumnos(){

		$busqueda = $_GET['term'];
		$q = strtolower($busqueda);

		$zona = DB_DataObject::factory("alumnos");
                $zona->selectAdd();
                $zona->selectAdd("alu_zona");
                $zona->whereAdd("alu_zona LIKE '%$busqueda%'");
                $zona->groupBy("alu_zona");
                $zona->orderBy("alu_zona ASC");
                $zona->find();
		$res = array();

		while ( $zona->fetch() ){
			$res[] = array("label" => utf8_encode($zona->alu_zona), "id" => $zona->alu_zona);
		}

		return json_encode($res);
	}

        private function listarCargosDocentes(){

		$busqueda = $_GET['term'];
		$q = strtolower($busqueda);

		$cargo = DB_DataObject::factory("docente");
                $cargo->selectAdd();
                $cargo->selectAdd("doc_cargo");
                $cargo->whereAdd("doc_cargo LIKE '%$busqueda%'");
                $cargo->groupBy("doc_cargo");
                $cargo->orderBy("doc_cargo ASC");
                $cargo->find();
		$res = array();

		while ( $cargo->fetch() ){
			$res[] = array("label" => utf8_encode($cargo->doc_cargo), "id" => $cargo->doc_cargo);
		}

		return json_encode($res);
	}
	
	private function getTablaReportes(){

		// variables del jqgrid
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		
		$sField = $_GET['searchField'];
		$sValue =  $_GET['searchString'];
		$sOper = $_GET['searchOper'];
		
		if(!$sidx) $sidx =1;
		
		// consulta en la base de datos
		$reportes = new DataObjects_Reportes();
		$count = $reportes->find();
		
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
		if(isset($sField)) $reportes->whereAdd("$sField LIKE '%$sValue%'");
		$reportes->orderBy("$sidx $sord");
		$reportes->limit($start, $limit);
		
		$reportes->find();
		$i = 0;
		while( $reportes->fetch() ){
			$reportes->getLinks();
			$responce->rows[$i]['id']=$reportes->getre_id();
    		$responce->rows[$i]['cell']=array($reportes->getre_id(), $reportes->_re_doc_id->doc_nombre, $reportes->getre_fecha_ingreso(), $reportes->_re_sec_id->sec_nombre, $reportes->getre_tip_id(), $reportes->getre_horas(),$reportes->getre_mot_id());
    		$i++;
		}
		
		return json_encode($responce);
		
	}
	
	
	private function getFechasBusqueda(){
		// si no tengo fecha de inicio, la consulta se refiere a la semana pasada
		$_POST['re_fecha_ini'] = $fecha_ini;
		if ( !(isset($fecha_ini)) ){
			$fecha_ini = strtotime('last Monday');
			// check if we need to go back in time one more week
			$fecha_ini = date('W', $fecha_ini)==date('W') ? $fecha_ini-7*86400 : $fecha_ini;
			$fecha_ini = date("Y-m-d", $fecha_ini);
		}  
		
		$_POST['re_fecha_fin'] = $fecha_fin;
		if ( !(isset($fecha_fin)) ){
			$fecha_fin = strtotime('last Friday');
			// check if we need to go back in time one more week
			$fecha_fin = date('W', $fecha_fin)==date('W') ? $fecha_fin-7*86400 : $fecha_fin;
			$fecha_fin = date("Y-m-d", $fecha_fin);
		} 
		
		return array($fecha_ini, $fecha_fin);
	}
	
	private function getReportes($fecha_inicio, $fecha_fin, $tipo, $filtro=null){
		
		$reportes = new DataObjects_Reportes();
		$reportes->selectAdd();
		$reportes->selectAdd("COUNT(re_tip_id) AS total, re_fecha_ingreso");
		$reportes->whereAdd("re_fecha_ingreso BETWEEN '$fecha_inicio' AND '$fecha_fin'");
		$reportes->whereAdd("re_tip_id = '$tipo'");
		$reportes->groupBy("re_fecha_ingreso");
		
		// si tiene un filtro extra, lo pongo en la consulta
		if($filtro) $reportes->whereAdd($filtro);

		$res = array("label" => $tipo, "resultset" => array() );
		// ejecuto la consulta
		if( $reportes->find() ) {
			while( $reportes->fetch() ){
				$res["resultset"][] = array("label" => $this->getDay($reportes->re_fecha_ingreso), "valueof" => $reportes->total);
			}
		}
		
		return $res;
	}
	
	private function getAccidentes($fecha_inicio, $fecha_fin, $tipo, $filtro=null){
		
		$accidentes = new DataObjects_Accidentes();
		$accidentes->selectAdd();
		$accidentes->selectAdd("COUNT(acc_tipo) AS total, acc_fecha_ingreso");
		$accidentes->whereAdd("acc_fecha_ingreso BETWEEN '$fecha_inicio' AND '$fecha_fin'");
		$accidentes->whereAdd("acc_tipo = '$tipo'");
		$accidentes->groupBy("acc_fecha_ingreso");
		
		// si tiene un filtro extra, lo pongo en la consulta
		if($filtro) $accidentes->whereAdd($filtro);

		$res = array("label" => $tipo, "resultset" => array() );
		// ejecuto la consulta
		if( $accidentes->find() ) {
			while( $accidentes->fetch() ){
				$res["resultset"][] = array("label" => $this->getDay($accidentes->acc_fecha_ingreso), "valueof" => $accidentes->total);
			}
		}
		
		return $res;
	}
	
	private function getHorasExtras($fecha_inicio, $fecha_fin, $filtro=null){
		
		$horasExtras = new DataObjects_Horas_extras();
		$horasExtras->selectAdd();
		$horasExtras->selectAdd("COUNT(he_id) AS total, he_fecha_ingreso");
		$horasExtras->whereAdd("he_fecha_ingreso BETWEEN '$fecha_inicio' AND '$fecha_fin'");
		$horasExtras->groupBy("he_fecha_ingreso");
		
		// si tiene un filtro extra, lo pongo en la consulta
		if($filtro) $horasExtras->whereAdd($filtro);

		$res = array("label" => $tipo, "resultset" => array() );
		// ejecuto la consulta
		if( $horasExtras->find() ) {
			while( $horasExtras->fetch() ){
				$res["resultset"][] = array("label" => $this->getDay($horasExtras->he_fecha_ingreso), "valueof" => $horasExtras->total);
			}
		}
		
		return $res;
	}
	
	private function getDay($string){
	
		$date = (strtotime($string))*1000;
		
		return $date;
	}
	
}
