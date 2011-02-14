<div class="contentBody">    <h3>Editar Partes</h3>    <p>A continuacion, podra editar todos los partes que fueron ingresados en el sistema. Es decir, cualquier parte que haya sido    aprobado, rechazado o todavia, pendiente de aprobacion.</p>    <p>La manera de hacerlo es seleccionando de la tabla "Partes Diarios" el parte que se quiera editar (es posible filtrar los partes    segun los criterios de busqueda deseados) y haciendo click en el. Luego, las otras 3 tablas se auto-completaran con la informacion del parte en cuestion. Si luego de hacer click en un parte, alguna tabla no se    completa automaticamente, quiere decir que el parte no posee informacion sobre dicha tabla.</p>    <p>Para mas informacion sobre como editar los partes una vez seleccionados, dirijase a al 'manual del administrador', ubicado en la    seccion 'Informes'</p>    <script src="js/jquery-grid/js/grid.locale-es.js" type="text/javascript"></script>    <script src="js/jquery-grid/js/jquery.jqGrid.min.js" type="text/javascript"></script>    <script type="text/javascript" src="js/jqgridTables.js"></script>    <div id="partes" class="gridTable" style="margin:20px auto">        <table id="partesTable"></table>        <div id="partesPager"></div>    </div>    <div id="reportes" class="gridTable" style="margin:20px auto">        <table id="reportesTable"></table>        <div id="reportesPager"></div>    </div>    <div id="accidentes" class="gridTable" style="margin:20px auto">        <table id="accidentesTable"></table>        <div id="accidentesPager"></div>    </div>    <div id="horasExtras" class="gridTable" style="margin:20px auto">        <table id="horasExtraTable"></table>        <div id="horasExtraPager"></div>    </div>    <script type="text/javascript">        function prepareParteForm(){            $("#par_fecha").datepicker({dateFormat:"yy-mm-dd"});            $("#par_sec_id").attr("disabled", "disabled");            $("#sec_nombre").attr("value", "").autocomplete({                minLength: 1,                source: "adminReports.php?action=listarSecciones",                focus: function(event, ui){                    $(this).val(ui.item.label);                    return false;                },                select: function(event, ui){                    $(this).val(ui.item.label);                    $("#par_sec_id").attr("value", ui.item.id);                    return false;                }            })        }        function prepareReportForm(){        	$("#re_motivo").autocomplete({                minLength: 2,                delay: 500,                source: "index.php?action=listarMotivosFiltrados",                focus: function(event, ui){                    $(this).val(ui.item.label);                    return false;                },                select: function(event, ui){                    $(this).val(ui.item.label);                    return false;                }            })            			// bloqueo el id del docente            $("#re_doc_id").attr("disabled", "disabled");            // autocomplete para los docentes        	$("#doc_nombre").autocomplete({                minLength: 2,                delay: 500,                source: "index.php?action=listarDocentes",                focus: function(event, ui){                    $(this).val(ui.item.label);                    return false;                },                select: function(event, ui){                    $(this).val(ui.item.label);                    $("#re_doc_id").attr("value", ui.item.id);                    return false;                }            }).data("autocomplete")._renderItem = function(ul, item){        		return $("<li></li>").data("item.autocomplete", item).append("<a>" + item.label + "<br><span class='desc'>" + item.desc + "</span></a>").appendTo(ul);        	};        }        function prepareAccidentesForm(){        	// bloqueo el id del docente            $("#acc_doc_id").attr("disabled", "disabled");            // autocomplete para los docentes        	$("#doc_nombre").autocomplete({                minLength: 2,                delay: 500,                source: "index.php?action=listarDocentes",                focus: function(event, ui){                    $(this).val(ui.item.label);                    return false;                },                select: function(event, ui){                    $(this).val(ui.item.label);                    $("#acc_doc_id").attr("value", ui.item.id);                    return false;                }            }).data("autocomplete")._renderItem = function(ul, item){        		return $("<li></li>").data("item.autocomplete", item).append("<a>" + item.label + "<br><span class='desc'>" + item.desc + "</span></a>").appendTo(ul);        	};        }        function prepareHorasExtrasForm(){        	// bloqueo el id del docente            $("#he_doc_id").attr("disabled", "disabled");            // autocomplete para los docentes        	$("#doc_nombre").autocomplete({                minLength: 2,                delay: 500,                source: "index.php?action=listarDocentes",                focus: function(event, ui){                    $(this).val(ui.item.label);                    return false;                },                select: function(event, ui){                    $(this).val(ui.item.label);                    $("#he_doc_id").attr("value", ui.item.id);                    return false;                }            }).data("autocomplete")._renderItem = function(ul, item){        		return $("<li></li>").data("item.autocomplete", item).append("<a>" + item.label + "<br><span class='desc'>" + item.desc + "</span></a>").appendTo(ul);        	};        }                $(document).ready(function(){            // tabla para los reportes                        var reColNames = ['Id', 'Doc. Id', 'Docente', 'Tipo', 'Horas', 'Motivo', 'Observacion del Administrador'];            var reColModel = [                {name:'re_id',index:'re_id'},                {name:'re_doc_id',index:'re_doc_id',  editable: true, width:0.01},                {name:'doc_nombre',index:'doc_nombre', editable: true},                {name:'re_tipo',index:'re_tipo', editable: true, edittype:'select', editoptions:{value:"Inasitencia:Inasistencia;Licencia:Licencia;Llegada Tarde:Llegada Tarde;Retiro Anticipado:Retiro Anticipado"}},                {name:'re_horas',index:'re_horas', editable: true},                {name:'re_motivo',index:'re_motivo', editable: true},                {name:'re_observacion',index:'re_observacion', editable: true},            ];            var reFieldsCsv = "fieldsCsv=re_id,re_doc_id,doc_nombre,re_tipo,re_horas,re_motivo,re_observacion";            var reTableRelations = "relations=docente";            var reId = "masterId=0&masterField=re_par_id";            var reUrl = 'adminReports.php?q=1&action=jqgridTable&table=reportes' + "&" + reFieldsCsv + "&" + reTableRelations + "&" + reId;            createTable("#reportesTable", reUrl, reColNames, reColModel, '#reportesPager', "Reportes", prepareReportForm);            // tabla para los accidentes            var accColNames = ['Id', 'Doc id', 'Docente', 'Tipo', 'Descripcion', 'Observacion del Administrador' ];            var accColModel = [                {name:'acc_id',index:'acc_id'},                {name:'acc_doc_id',index:'acc_doc_id',  editable: true, width:0.01},                {name:'doc_nombre',index:'doc_nombre', editable: true},                {name:'acc_tipo',index:'acc_tipo', editable: true, edittype:'select', editoptions:{value:"Profesor:Profesor;Alumno:Alumno"}},                {name:'acc_descripcion',index:'acc_descripcion', editable: true},                {name: 'acc_observacion',index: 'acc_observacion',editable: true}            ];            var accFieldsCsv = "fieldsCsv=acc_id,acc_doc_id,doc_nombre,acc_tipo,acc_descripcion,acc_observacion";            var accTableRelations = "relations=docente";            var accId = "masterId=0&masterField=acc_par_id";            var accUrl = 'adminReports.php?q=1&action=jqgridTable&table=accidentes' + "&" + accFieldsCsv + "&" + accTableRelations + "&" + accId;            createTable("#accidentesTable", accUrl, accColNames, accColModel, '#accidentesPager', "Accidentes", prepareAccidentesForm);            // tabla para las horas Extras            var heColNames = ['Id', 'Doc Id', 'Docente', 'Horas', 'Descripcion', 'Observacion del Administrador'];            var heColModel = [                {name:'he_id',index:'he_id'},                {name:'he_doc_id',index:'he_doc_id',  editable: true, width:0.01},                {name:'doc_nombre',index:'doc_nombre', editable: true},                {name:'he_cantidad_horas',index:'he_cantidad_horas', editable: true},                {name:'he_descripcion',index:'he_descripcion', editable: true, edittype:"textarea"},                {name:'he_observacion',index:'he_observacion', editable: true, edittype:"textarea"}            ];            var heFieldsCsv = "fieldsCsv=he_id,he_doc_id,doc_nombre,he_cantidad_horas,he_descripcion,he_observacion";            var heTableRelations = "relations=docente";            var heId = "masterId=0&masterField=he_par_id";            var heUrl = 'adminReports.php?q=1&action=jqgridTable&table=horas_extras' + "&" + heFieldsCsv + "&" + heTableRelations + "&" + heId;            createTable("#horasExtraTable", heUrl, heColNames, heColModel, '#horasExtraPager', "Horas Extra", prepareHorasExtrasForm);            // tabla para los partes            var parColNames = ['Id', 'Aprobado', 'Fecha', 'Observaciones', 'Seccion ID', 'Seccion'];            var parColModel = [                {name:'par_id',index:'par_id',},                {name:'par_aprobado',index:'par_aprobado', editable: true, edittype:'select', editoptions:{value:"1:Aprobado;0:Pendiente;-1:Rechazado"}},                {name:'par_fecha',index:'par_fecha', editable: true, sortable:true},                {name:'par_observaciones',index:'par_observaciones', editable: true},                {name:'par_sec_id',index:'par_sec_id',  editable: true, width:0.01},                {name:'sec_nombre',index:'sec_nombre',  editable: true},            ];            var parFieldsCsv = "fieldsCsv=par_id,par_aprobado,par_fecha,par_observaciones,par_sec_id,sec_nombre";            var parTableRelations = "relations=secciones";            var parUrl = 'adminReports.php?q=1&action=jqgridTable&table=partes' + "&" + parFieldsCsv + "&" + parTableRelations;            createTable("#partesTable", parUrl, parColNames, parColModel, '#partesPager', "Partes Diarios", prepareParteForm);            // agrego / modifico parametros del grid            jQuery("#partesTable").jqGrid('setGridParam',{                onSelectRow: function(id){                    // cargo todos los grid dependientes                    // // reportes                    var reId = "masterId=" + id +"&masterField=re_par_id";                    var reUrl = 'adminReports.php?q=1&action=jqgridTable&table=reportes' + "&" + reFieldsCsv + "&" + reTableRelations + "&" + reId;                    jQuery("#reportesTable").jqGrid('setGridParam',{url:reUrl,page:1})                    .trigger('reloadGrid');                    // accidentes                    var accId = "masterId=" + id +"&masterField=acc_par_id";                    var accUrl = 'adminReports.php?q=1&action=jqgridTable&table=accidentes' + "&" + accFieldsCsv + "&" + accTableRelations + "&" + accId;                    jQuery("#accidentesTable").jqGrid('setGridParam',{url:accUrl,page:1})                    .trigger('reloadGrid');                    // horas extras                    var heId = "masterId=" + id +"&masterField=he_par_id";                    var heUrl = 'adminReports.php?q=1&action=jqgridTable&table=horas_extras' + "&" + heFieldsCsv + "&" + heTableRelations + "&" + heId;                    jQuery("#horasExtraTable").jqGrid('setGridParam',{url:heUrl,page:1})                    .trigger('reloadGrid');                }            })        })    </script></div>                }        }        }