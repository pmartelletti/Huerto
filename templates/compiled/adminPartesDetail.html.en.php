<div class="contentBody">
	<script src="js/jquery-grid/js/grid.locale-es.js" type="text/javascript"></script>
	<script src="js/jquery-grid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/jqgridTables.js"></script>
	
	<style type="text/css">
		.jqgrow {
			cursor: pointer;
		}
	</style>

	<h3>Informes individuales</h3>

	<div id="partes" class="gridTable" style="margin: 20px auto">
		<table id="partesTable"></table>
		<div id="partesPager"></div>
	</div>
	
	<div>
		<h3>Rango de reportes</h3>
		<p>A continuacion, puede establecer los criterios de busqueda deseados y, con los resultados obtenidos, imprimir
		un solo PDF con todos los partes que esten en los mismos. Mientras mas especifica sea su busqueda, mas especificos
		seran los partes que se imprimiran.</p>
		
		<form id="rangoPDF" action="#">
		
			<p>
				<label for="par_fecha_ini">Fecha inicial: </label>
				<?php echo $this->elements['par_fecha_ini']->toHtml();?>
			</p>
			
			<p>
				<label for="par_fecha_fin">Fecha final: </label>
				<?php echo $this->elements['par_fecha_fin']->toHtml();?>
			</p>
			
			<p>
				<label for="par_aprobado">Estado del parte: </label>
				<?php echo $this->elements['par_aprobado']->toHtml();?>
			</p>
			
			<p>
				<label for="par_sec_id">Seccion: </label>
				<?php echo $this->elements['par_sec_id']->toHtml();?>
			</p>
			
			<p>
				<?php echo $this->elements['send_form']->toHtml();?>
			</p>
			
		</form>
		
		<h4>Resultados</h4>
		<table id="partes_res">
			<thead>
				<tr>
					<th>ID</th>
					<th>Seccion</th>
					<th>Fecha</th>
					<th>Estado del Parte</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		
		<div id="pdfDownload" style="margin: 10px 20px"></div>
	
	</div>

	<script type="text/javascript">

		function sendResultForm() {
			// envio el formulario de los resultados
			$("form#rangoPDF").submit(function(){
				var params = $(this).serialize() + "&action=getResultadosPdf"; 
				$.post("adminReports.php", params, function(response){
					if( response.statusCode == "0" ){
						// esta todo ok
						var data = response.data;
						var result = "";
						var srcID = "";
						for(i=0; i < data.length; i++){
							// agrego las filas diamicamente
							result += "<tr><td>" + data[i].par_id + "</td><td>" + data[i].par_secccion + "</td><td>" + data[i].par_fecha + "</td><td>" + data[i].par_estado + "</td></tr>";
							srcID += data[i].par_id + "-";
						}
						$("table#partes_res tbody").html(result);
						$("#pdfDownload").html("<a href='adminReports.php?action=informePdf&idParte=" + srcID + "' target='_blank' ><img src='images/guia_pdf' /><p>Descargar resultados en PDF</p></a>");
					} else {
						// hubo un error, muestro el error
						var result = "<tr><td colspan='4' class='error'>" + response.statusMsg + "</td></tr>";
						$("table#partes_res tbody").html(result);
					}
				}, "json");
				
				return false;
			})
		}

		function prepareParteForm(){
			return;
		}

		$(document).ready(function(){
					// preparo el formulario de partes totales
					
					$("#par_fecha_ini, #par_fecha_fin").datepicker({ dateFormat: 'yy-mm-dd' });
					
					// envio el formulario
					sendResultForm();
			
                    // tabla para los partes
                    var parColNames = ['Id', 'Aprobado', 'Fecha', 'Observaciones', 'Seccion ID', 'Seccion'];
                    var parColModel = [
                            {name:'par_id',index:'par_id',},
                            {name:'par_aprobado',index:'par_aprobado', editable: true, edittype:'select', editoptions:{value:"1:Aprobado;0:Pendiente"}},
                            {name:'par_fecha',index:'par_fecha', editable: true, sortable:true},
                            {name:'par_observaciones',index:'par_observaciones', editable: true},
                            {name:'par_sec_id',index:'par_sec_id',  editable: true, width:0.01},
                            {name:'sec_nombre',index:'sec_nombre',  editable: true},
                            ];
                    var parFieldsCsv = "fieldsCsv=par_id,par_aprobado,par_fecha,par_observaciones,par_sec_id,sec_nombre";
                    var parTableRelations = "relations=secciones";
                    var parUrl = 'adminReports.php?q=1&action=jqgridTable&table=partes' + "&" + parFieldsCsv + "&" + parTableRelations;
                    createTable("#partesTable", parUrl, parColNames, parColModel, '#partesPager', "Partes Diarios", prepareParteForm, "user");
                    // agrego / modifico parametros del grid
                    jQuery("#partesTable").jqGrid('setGridParam',{
                            onSelectRow: function(id){
                                    // abro el PDF en una vista aparte
                                    window.open("adminReports.php?action=informePdf&idParte=" + id);
                                    return false;
                            }
                    })

		})
	</script>

</div>

