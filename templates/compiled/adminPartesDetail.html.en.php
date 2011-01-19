<div class="contentBody">
	<script src="js/jquery-grid/js/grid.locale-es.js" type="text/javascript"></script>
	<script src="js/jquery-grid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/jqgridTables.js"></script>
	
	<style type="text/css">
		.jqgrow {
			cursor: pointer;
		}
	</style>

	<div id="partes" class="gridTable" style="margin: 20px auto">
		<table id="partesTable"></table>
		<div id="partesPager"></div>
	</div>

	<script type="text/javascript">

		function prepareParteForm(){
			return;
		}

		$(document).ready(function(){
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
                    createTable("#partesTable", parUrl, parColNames, parColModel, '#partesPager', "Partes Diarios", prepareParteForm);
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
