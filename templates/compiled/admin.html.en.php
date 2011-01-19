<link rel="stylesheet" type="text/css" media="screen" href="js/jquery-grid/css/ui.jqgrid.css" />
<script src="js/jquery-grid/js/grid.locale-es.js" type="text/javascript"></script>
<script src="js/jquery-grid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/ingresoReportes.js"></script>


<div id="content">
	<table id="list2"></table>
	<div id="pager2"></div>
</div>

<script>
	
	$(document).ready(function(){
		var colNames = ['Reporte Id','Docente', 'Fecha', 'Seccion','Tipo','C. Horas','Motivo'];
		var colModel = [
   		{name:'re_id',index:'re_id', width:55},
   		{name:'re_doc_id',index:'re_doc_id', width:200, editable:true},
   		{name:'re_fecha_ingreso',index:'re_fecha_ingreso', width:80, editable:true},
   		{name:'re_sec_id',index:'re_sec_id', width:60, align:"right",editable:true, edittype:"select",editoptions:{value:"inicial:Inicial;primaria:Primaria;secundaria:Secundaria;polimodal:Polimodal"}},
   		{name:'re_tip_id',index:'re_tip_id', width:60, align:"right",editable:true, edittype:"select",editoptions:{value:"Tarde:Tarde;Retiro:Retiro;Inasistencia:Inasistencia"}},		
   		{name:'re_horas',index:'re_horas', width:60,align:"right", editable:true},		
   		{name:'re_mot_id',index:'re_mot_id', width:100, sortable:false, editable:true}		
   	];
		createTable("#list2", 'adminReports.php?q=1&action=tableReportes', colNames, colModel, '#pager2', "Partes diarios");
	});
	
function createTable(tableid, url1, colnames, colmodel, pagerid, caption){
	jQuery(tableid).jqGrid({
   	url:url1,
	datatype: "json",
   	colNames:colnames,
   	colModel:colmodel,
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: pagerid,
    viewrecords: true,
    caption:caption,
    editurl:"listado.php" // que hace editurl?? investigar esto en la documentation
	});
	// ver como funcionan las opciones del buscador
	var options = {}
	jQuery("#list2").jqGrid('filterToolbar',options);
	
	jQuery("#list2").jqGrid('navGrid','#pager2',
	{}, //options
	{
		height:200,
		reloadAfterSubmit:false,
		afterShowForm: function(id){
			prepareForm("hola");
		} 
	}, // edit options
	{
		height:280,
		reloadAfterSubmit:false,
		afterShowForm: function(id){
			prepareForm("hola");
		}
	}, // add options
	{reloadAfterSubmit:false}, // del options
	{
		odata : ['contiene'],
		sopt:['eq']} // search options
	);
}
	
	


function prepareForm(p1){
	
	$("#re_fecha_ingreso").datepicker({dateFormat: "yy-mm-dd"});
	$("#re_doc_id").autocomplete({
		minLength: 2,
		source: "index.php?action=listarDocentes",
		focus: function(event, ui){
			$(selector).val(ui.item.label);
			return false;
		},
		select: function(event, ui){
			$("#re_doc_id").val(ui.item.id);
			
			return false;
			}
	}).data("autocomplete")._renderItem = function(ul, item){
		return $("<li></li>").data("item.autocomplete", item).append("<a>" + item.label + "<br><span class='desc'>" + item.desc + "</span></a>").appendTo(ul);
	};
	
}

</script>