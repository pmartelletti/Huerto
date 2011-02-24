<?php

require_once 'classes/fpdf/fpdf.php';
require_once 'classes/DbConfig.class.php';
require_once 'DB/DataObject.php';

/**
 * Description of reportePdf
 *
 * @author pablo
 */
class reportePdf extends FPDF {

    private $parte;
    
    static private $estados = array(
    	"-1" => "Rechazado",
    	"0" => "Pendiente",
    	"1" => "Aprobado"
    );

    public function reportePdf($id){

        parent::FPDF();

        DbConfig::setup();

        $this->parte = DB_DataObject::factory("partes");
        $this->parte->get( $id );
        $this->parte->getLinks();

    }

    //Cabecera de página
    public function Header()
    {
        //Logo
        $this->Image('images/escudo.gif',180,10, 10, 10 );
        $this->Ln(15);
        //Arial bold 15
        $this->SetFont('Arial','BU',20);
        //Título
        $this->Cell(0, 0 ,'Detalle del Parte Diario',0 ,0,'C');
        //Salto de línea
        $this->Ln(20);
    }

    //Pie de página
    public function Footer()
    {
        //Posición: a 1,5 cm del final
        $this->SetY(-15);
        //Arial italic 8
        $this->SetFont('Arial','I',8);
        //Número de página
        $this->Cell(0,10,'Pagina '.$this->PageNo(),0,0,'C');
    }


    public function setContent(){

        // relleno el contenido del pdf con los datos del parte
        $this->AddPage();

        // INFORMACION GENERAL DEL PARTE
        $this->Cell(0, 5, "" , "LTR");
        $this->Ln();
        $this->SetFontSize(16);
        $this->Cell(0, 5, "Informacion del reporte" , "LR", 1);
        $this->Cell(0, 7, "" , "LR", 1);
        $this->SetFont("Arial", "", 12);

        // detalles del partes
        $this->SetFont('Arial', 'B');
        $this->Cell(35, 7, "ID del reporte: ", "L", 0);
        $this->SetFont('Arial', '');
        $this->Cell(70, 7, $this->parte->par_id);
        $this->SetFont('Arial', 'B');
        $this->Cell(30, 7, "Fecha: ");
        $this->SetFont('Arial', '');
        $this->Cell(0, 7, $this->parte->par_fecha, "R", 1);

        $this->SetFont('Arial', 'B');
        $this->Cell(35, 7, "Seccion: ", "L", 0);
        $this->SetFont('Arial', '');
        $this->Cell(70, 7, $this->parte->_par_sec_id->sec_nombre);
        $this->SetFont('Arial', 'B');
        $this->Cell(30, 7, "Secretaria: ");
        $this->SetFont('Arial', '');
        $this->Cell(0, 7, $this->parte->_par_us_id->us_nombre, "R", 1);
        
        $this->SetFont('Arial', 'B');
        $this->Cell(35, 7, "Observaciones: " , "L");
        $this->SetFont('Arial', '');
        $this->Cell(0, 7, $this->parte->par_observaciones, "R", 1);
        
        $this->SetFont('Arial', 'B');
        $this->Cell(35, 7, "Estado: " , "L");
        $this->SetFont('Arial', '');
        $this->Cell(0, 7, reportePdf::$estados[$this->parte->par_aprobado], "R", 1);
        
        if( $this->parte->par_aprobado != "0") {

        	
        	$this->SetFont('Arial', 'B');
	        $this->Cell(35, 7, "Responsable: ", "L", 0);
	        $this->SetFont('Arial', '');
	        $this->Cell(70, 7, $this->parte->_par_us_id_aprobacion->us_nombre);
	        $this->SetFont('Arial', 'B');
	        $this->Cell(30, 7, "Fecha: ");
	        $this->SetFont('Arial', '');
	        $this->Cell(0, 7, date("d-m-Y", $this->parte->par_fecha_aprobacion), "R", 1);
        	
        }
        
        

        $this->Cell(0, 7, "", "LBR", 1);

        $this->Ln(5);

        // busco los reportes y para cada uno, creo un reporte
        $reportes = DB_DataObject::factory("reportes");
        $reportes->re_par_id = $this->parte->par_id;
        $num_re = $reportes->find();
        // contador para las separaciones
        $i = 1;
        if ( $num_re > 0 ){
            // abro la tabla
            $this->Cell(0, 5, "" , "LTR");
            $this->Ln();
            // titulo de la "caja" de partes
            // INFORMACION GENERAL DEL PARTE
            $this->SetFont("Arial", "B", 16);
            $this->Cell(0, 5, "Partes" , "LR", 1);
            $this->Cell(0, 7, "" , "LR", 1);
            $this->SetFont("Arial", "", 12);
            // hay reportes en el parte, los muestro en el pdf
            while( $reportes->fetch() ){
                // creo un reporte y lo agrego al pdf
                $this->createReporte($reportes);
                if( $i != $num_re){
                    $this->crearBordeSeparador();
                }
                $i++;
            }

            // cierro la tabla
            $this->Cell(0, 0, "", "LBR", 1);
        }

        $this->Ln(5);


         // busco los accidentes y para cada uno, creo un reporte
        $accidentes = DB_DataObject::factory("accidentes");
        $accidentes->acc_par_id = $this->parte->par_id;
        $num_acc = $accidentes->find();
        // contador para las separaciones
        $i = 1;
        if ( $num_acc > 0 ){
            // abro la tabla
            $this->Cell(0, 5, "" , "LTR");
            $this->Ln();
            // titulo de la "caja" de partes
            // INFORMACION GENERAL DEL PARTE
            $this->SetFont("Arial", "B", 16);
            $this->Cell(0, 5, "Accidentes" , "LR", 1);
            $this->Cell(0, 7, "" , "LR", 1);
            $this->SetFont("Arial", "", 12);
            // hay reportes en el parte, los muestro en el pdf
            while( $accidentes->fetch() ){
                // creo un reporte y lo agrego al pdf
                $this->createAccidente($accidentes);
                if( $i != $num_acc){
                    $this->crearBordeSeparador();
                }
                $i++;
            }

            // cierro la tabla
            $this->Cell(0, 0, "", "LBR", 1);
        }

        $this->Ln(5);


         // busco los accidentes y para cada uno, creo un reporte
        $horasExtras = DB_DataObject::factory("horas_extras");
        $horasExtras->he_par_id = $this->parte->par_id;
        $num_he = $horasExtras->find();
        // contador para las separaciones
        $i = 1;
        if ( $num_he > 0 ){
            // abro la tabla
            $this->Cell(0, 5, "" , "LTR");
            $this->Ln();
            // titulo de la "caja" de partes
            // INFORMACION GENERAL DEL PARTE
            $this->SetFont("Arial", "B", 16);
            $this->Cell(0, 5, "Horas Extras" , "LR", 1);
            $this->Cell(0, 7, "" , "LR", 1);
            $this->SetFont("Arial", "", 12);
            // hay reportes en el parte, los muestro en el pdf
            while( $horasExtras->fetch() ){
                // creo un reporte y lo agrego al pdf
                $this->createHorasExtras($horasExtras);
                if( $i != $num_he){
                    $this->crearBordeSeparador();
                }
                $i++;
            }

            // cierro la tabla
            $this->Cell(0, 0, "", "LBR", 1);
        }

        
    }

    private function crearBordeSeparador(){
        $this->Cell(20, 5, "", "L" );
        $this->Cell(150, 5, "", "B");
        $this->Cell(0, 5, "", "R",1);
        $this->Cell(0, 5, "", "LR",1);
    }

    private function createReporte($reporte){

        $reporte->getLinks();

        // detalles del partes
        $this->SetFont('Arial', 'B');
        $this->Cell(35, 7, "ID: ", "L", 0);
        $this->SetFont('Arial', '');
        $this->Cell(100, 7, $reporte->re_id);
        $this->SetFont('Arial', 'B');
        $this->Cell(20, 7, "Tipo: ");
        $this->SetFont('Arial', '');
        $this->Cell(0, 7, $reporte->re_tipo, "R", 1);

        $this->SetFont('Arial', 'B');
        $this->Cell(35, 7, "Docente: ", "L", 0);
        $this->SetFont('Arial', '');
        $this->Cell(100, 7, $reporte->_re_doc_id->doc_nombre);
        $this->SetFont('Arial', 'B');
        $this->Cell(20, 7, "Horas: ");
        $this->SetFont('Arial', '');
        $this->Cell(0, 7, $reporte->re_horas, "R", 1);

        $this->SetFont('Arial', 'B');
        $this->Cell(35, 7, "Motivo: " , "L");
        $this->SetFont('Arial', '');
        $this->Cell(0, 7, $reporte->re_motivo, "R", 1);

        $this->SetFont('Arial', 'B');
        $this->Cell(35, 7, "Observaciones: " , "L");
        $this->SetFont('Arial', '');
        $this->Cell(0, 7, $reporte->re_observacion, "R", 1);

        


    }

    private function createAccidente($accidente){

        $accidente->getLinks();

        // detalles del accidente
        $this->SetFont('Arial', 'B');
        $this->Cell(35, 7, "ID: ", "L", 0);
        $this->SetFont('Arial', '');
        $this->Cell(80, 7, $accidente->acc_id);
        $this->SetFont('Arial', 'B');
        $this->Cell(30, 7, "Tipo: ");
        $this->SetFont('Arial', '');
        $this->Cell(0, 7, $accidente->acc_tipo, "R", 1);

        $this->SetFont('Arial', 'B');
        $this->Cell(35, 7, "Docente: ", "L", 0);
        $this->SetFont('Arial', '');
        $this->Cell(80, 7, $accidente->_acc_doc_id->doc_nombre);
        $this->SetFont('Arial', 'B');
        $this->Cell(30, 7, "Reporte ART: ");
        $this->SetFont('Arial', '');
        if( $accidente->acc_reporte_art) $art = "Si";
        else $art = "No";
        $this->Cell(0, 7, $art, "R", 1);

        $this->SetFont('Arial', 'B');
        $this->Cell(35, 7, "Descripcion: " , "L");
        $this->SetFont('Arial', '');
        $this->Cell(0, 7, $accidente->acc_descripcion, "R", 1);

    }

    private function createHorasExtras($horasExtras){

        $horasExtras->getLinks();

        // detalles del accidente
        $this->SetFont('Arial', 'B');
        $this->Cell(35, 7, "ID: ", "L", 0);
        $this->SetFont('Arial', '');
        $this->Cell(100, 7, $horasExtras->he_id);
        $this->SetFont('Arial', 'B');
        $this->Cell(20, 7, "Horas: ");
        $this->SetFont('Arial', '');
        $this->Cell(0, 7, $horasExtras->he_cantidad_horas, "R", 1);

        $this->SetFont('Arial', 'B');
        $this->Cell(35, 7, "Docente: ", "L", 0);
        $this->SetFont('Arial', '');
        $this->Cell(0, 7, $horasExtras->_he_doc_id->doc_nombre, "R", 1);
        
        $this->SetFont('Arial', 'B');
        $this->Cell(35, 7, "Descripcion: " , "L");
        $this->SetFont('Arial', '');
        $this->Cell(0, 7, $horasExtras->he_descripcion, "R", 1);

        $this->SetFont('Arial', 'B');
        $this->Cell(35, 7, "Observacion: " , "L");
        $this->SetFont('Arial', '');
        $this->Cell(0, 7, $horasExtras->he_observacion, "R", 1);
        
    }

    public function getPdf(){
        // devuelvo el contenido del pdf en una ventana del navegar
    }



}



?>
