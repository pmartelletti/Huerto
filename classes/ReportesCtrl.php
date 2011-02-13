<?php

require_once 'Docente.php';
require_once 'AdminView.class.php';
require_once 'ReportesView.class.php';
require_once 'Reportes.php';
require_once 'Accidentes.php';
require_once 'Horas_extras.php';
require_once 'ReportesIndexView.class.php';
require_once 'TabsReportesView.class.php';
require_once 'TabsAdminView.class.php';
require_once 'classes/phpMailer/class.phpmailer.php';
require_once 'classes/reportePdf.class.php';

class ReportesController {
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function ReportesController(){
		
	}
	
	public static function getPdfParteRechazado($id){
		
		$pdf=new reportePdf($id);
		$pdf->setContent();

		return $pdf->Output();
	}
	
	public function getView($view){
		
		switch($view){

                        case "reportesIndex":

                            return $this->getReportesIndexView();
                    
				
			case "admin":
				
				return $this->getAdminView();
				
			default:
				return null;
		}
		
	}

        private function getReportesIndexView(){

            $content = new ReportesIndexView();
            $content->setDisplayOptions();
            return $content->getDisplay();
            
        }
	
	private function getAdminView(){
		$content = new AdminView();
		return $content->getDisplay();
	}

        private function getTabsView(){

            $tab = $_GET['tabView'];

            $content = new TabsReportesView();
            $content->setDisplayOptions($tab);
            return $content->getDisplay();

	}
	
	private function getReportesView(){
		
		$content = new ReportesView();
		$seccion = SessionVars::getSeccion();
		$usuario = SessionVars::getUsuario();
		$content->setDisplayOptions($seccion, $usuario);
		return $content->getDisplay();
				
	}
	
	public function requestAsyncAction($action){
		
		switch ($action){
			
			case "ingresoReportes":
				
				$res = array();
				
				$id_parte = $this->insertParte();
				
				if( $this->insertReportes($id_parte) 
						&& $this->insertAccidentes($id_parte) 
						&& $this->insertHorasExtras($id_parte) )
					{
						$res["status"] = "El parte se ha ingresado correctamente";
						$res["code"] = 0;
					} else {
						$res["status"] = "Hubo un error al procesar el parte diario. Por favor, contactese con el administrador del sistema.";
						$res["code"] = 100;
					}
				
				return json_encode($res);

                        case "ingresarReportes":
                            
                            return $this->getReportesView();
				
			case "listarDocentes":
				
				return $this->listarDocentes();

                         case "tabs":

				return $this->getTabsView();
				
			case "listarMotivos":
				
				return $this->listarMotivos();
				
			case "listarSeccionesAdmin":
				return $this->listarSeccionesAdmin();
				
			case "listarMotivosFiltrados":
				
				return $this->listarMotivosFiltrados();

                        case "sendMail":

                            echo $this->enviarInformeError();
			
			default:
				break;
		}
		
	}

        private function enviarInformeError(){
        	
        	$seccion = DB_DataObject::factory("secciones");
        	$seccion->sec_nombre = SessionVars::getSeccion();
        	$seccion->find(); $seccion->fetch();
        	
        	$fecha = date("Y-m-d");
        	$sec_id = $seccion->sec_id;
        	
        	
        	// ingreso los datos del informe de error en la base de datos
        	$informe = DB_DataObject::factory("informe_errores");
        	$informe->ie_fecha = $fecha;
        	$informe->ie_sec_id = $seccion->sec_id;
        	$informe->setFrom($_POST);
        	
        	if( !($informe->insert()) ){
        		return json_encode( array("statusCode" => "200", "statusMsg" => "Error al enviar el email. Intentelo mas tarde nuevamente.") );
        	}

            $mail = new PHPMailer();
            $mail->From     = "noreply@parteshuerto.com.ar"; // Mail de origen
            $mail->FromName = "Soporte Tecnico Huerto"; // Nombre del que envia
            $mail->AddAddress("pmartelletti@gmail.com"); // Mail destino, podemos agregar muchas direcciones
            $mail->AddReplyTo("soporte@parteshuerto.com.ar"); // Mail de respuesta

            $mail->WordWrap = 80; // Largo de las lineas
            $mail->IsHTML(true); // Podemos incluir tags html
            $mail->Subject  =  "Contacto de secretaria: seccion ". SessionVars::getSeccion();

            $texto = "Este mail se ha generado automaticamente porque una secretaria ha solicitado ayuda en el sistema de partes diarios. <br><br>
            <b>Fecha de envio: </b>$fecha<br/>
            <b>Motivo de la consulta: </b>" . $_POST['ie_motivo'] . "<br/>
            <b>Detalles del error: </b>" . $_POST['ie_detalle'] . "<br/>
            <b>Observaciones: </b>" . $_POST['ie_observaciones'] . "<br/><br/>
            
            Por favor, pongase en contacto con la secretaria de la seccion lo antes posible. Muchas gracias, <br><br>
            Soporte Tecnico.";
            $mail->Body     =  $texto;
            $mail->AltBody  =  strip_tags($mail->Body); // Este es el contenido alternativo sin html

            $mail->IsSMTP(); // vamos a conectarnos a un servidor SMTP
            $mail->Host = "mail.parteshuerto.com.ar"; // direccion del servidor
            $mail->SMTPAuth = true; // usaremos autenticacion
            $mail->Username = "noreply@parteshuerto.com.ar"; // usuario
            $mail->Port = 26;
            $mail->Password = "0220404"; // contraseña

            if( $mail->Send() ){
                return json_encode( array("statusCode" => "100", "statusMsg" => "El email se ha enviado correctamente") );
            } else {
                return json_encode( array("statusCode" => "200", "statusMsg" => "Error al enviar el email. Intentelo mas tarde nuevamente.") );
            }
                    
            
        }
	
	private function listarMotivos(){
		$motivos = DB_DataObject::factory("motivos");
		$motivos->find();
		
		$res = array();
		while( $motivos->fetch() ){
			$res[] = utf8_encode($motivos->mot_descripcion);	
		}
		
		return json_encode($res);
	}
	
	private function listarMotivosFiltrados() {
		$busqueda = $_GET['term'];
		$q = strtolower($busqueda);

		$res = array();
				
		$motivos = DB_DataObject::factory("motivos");
		$motivos->query("SELECT * FROM {$motivos->__table} WHERE mot_descripcion LIKE '%$busqueda%' ORDER BY mot_descripcion ASC");

		while ( $motivos->fetch() ){
			$res[] = array("label" => utf8_encode($motivos->mot_descripcion), "id" => $motivos->mot_descripcion, "desc" => $motivos->mot_id, );
		}
		
		return json_encode($res);
	}
	
	private function listarDocentes(){
		
		$busqueda = $_GET['term'];
		$filtro = $_GET['filtro'];
		$q = strtolower($busqueda);

		$res = array();
		
		if ( !(isset($filtro)) or $filtro=="Profesor" ){
						
			$docente = new DataObjects_Docente();
			$docente->query("SELECT * FROM {$docente->__table} WHERE doc_nombre LIKE '%$busqueda%' ORDER BY doc_nombre");
	
			while ( $docente->fetch() ){
				$descripcion = $docente->getdoc_cargo() . " (". $docente->getdoc_seccion() .") " . $docente->getdoc_revista() ;
				$addData = array("cargo"=>$docente->getdoc_cargo(), "revista"=> $docente->getdoc_revista());
				$res[] = array("label" => utf8_encode($docente->getdoc_nombre()), "id" => $docente->getdoc_id(), "desc" => $descripcion, "addData"=>$addData );
			}
		} else {
			
			// la consulta es de alumnos
			$alumno = DB_DataObject::factory("alumnos");
			$alumno->query("SELECT * FROM {$alumno->__table} WHERE alu_nombre LIKE '%$busqueda%' ORDER BY alu_nombre");
			while ( $alumno->fetch() ){
				$descripcion = $alumno->alu_zona . " (". $alumno->alu_identificador .")";
				$addData = array("cargo"=>"", "revista"=> "");
				$res[] = array("label" => utf8_encode($alumno->alu_nombre), "id" => $alumno->alu_id , "desc" => $descripcion, "addData"=>$addData );
			}
			
		}
		return json_encode($res);
	}
	
	private function insertParte(){
		
		$parte = DB_DataObject::factory("partes");
		$parte->setFrom($_POST);
		return $parte->insert();
		
	}
	
	private function listarSeccionesAdmin(){
		// FUNCION QUE SIRVE PARA AGRUPAR LAS DISTINTAS ZONAS, CARGOS, SECCIONES, ETC, PARA INCLUIR EN EL JS
		$cargos = DB_DataObject::factory('alumnos');
		$cargos->groupBy("alu_zona");
		$cargos->orderBy("alu_zona ASC");
		$cargos->find();
		
		$result = "";
		
		while( $cargos->fetch() ){
			$result .= $cargos->alu_zona . ":" . $cargos->alu_zona . ";";
		}
		
		return $result;
		
	}
	
	private function insertReportes($id_parte){
		// poner private esto
		
		// ingreso todos los reportes
		$res = array();
		if ( isset($_POST['re_actual'])){
			foreach($_POST['re_actual'] as $i){
				
				$reporte = new DataObjects_Reportes();
				$reporte->re_par_id = $id_parte;
				$reporte->re_doc_id = $_POST['re_doc_id'][$i];
				$reporte->re_horas = $_POST['re_horas'][$i];
				$reporte->re_tipo = $_POST['re_tipo'][$i];
				$reporte->re_motivo = $_POST['re_motivo'][$i];
				$reporte->re_observacion = $_POST['re_observacion'][$i];
				
				if(!$reporte->insert()){
					return false;
				}
			}	
		}
		return true;
		
	}
	
	private function insertAccidentes($id_parte){
		
		if ( isset( $_POST['acc_actual'])){
			foreach ($_POST['acc_actual'] as $i){
				
				$accidente = new DataObjects_Accidentes();
				$accidente->acc_par_id = $id_parte;
				
				$tipo = $_POST['acc_tipo'][$i];
				if( $tipo == "Alumno"){
					$accidente->acc_alu_id = $_POST['acc_doc_id'][$i];
				} else {
					$accidente->acc_doc_id = $_POST['acc_doc_id'][$i];
				}				
				$accidente->acc_reporte_art = $_POST['acc_reporte_art'][$i];
				$accidente->acc_descripcion = $_POST['acc_descripcion'][$i];;
				$accidente->acc_tipo = $_POST['acc_tipo'][$i];
				
				if(!$accidente->insert()){
					return false;
				}
			}
		}
		
		return true;
		
	}
	
	private function insertHorasExtras($id_parte){
		
		if (isset($_POST['he_actual'])){
			foreach ($_POST['he_actual'] as $i){
				
				$extras = new DataObjects_Horas_extras();
				$extras->he_par_id = $id_parte;
				$extras->he_cantidad_horas = $_POST['he_cantidad_horas'][$i];
				$extras->he_doc_id = $_POST['he_doc_id'][$i];
				$extras->he_descripcion = $_POST['he_descripcion'][$i];
				
				if(!$extras->insert()){
					return false;
				}
			}
		}
				
		return true;
	}	
}