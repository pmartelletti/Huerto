/**
 * @author pablo
 */

$(document).ready(function(){

	// creacion de la validacion del formulario
	var reglas = {fecha: "required", re_docente: "required"};
	var mensajes = {fecha: "Debe ingresar una fecha de parte", re_docente: "Ingrese el nombre de un docente"};
	sendFormAjax("#reportes", reglas, mensajes);
	
	
})
 
function agregarTr(tableId, trNumber, contador, prefix){
	
	var trId = 'tr_id_' + contador ;
	
	var trHtml = ['<tr id="' + trId + '"><td><span class="ui-icon ui-icon-close deleteRow" id="delete-' + trId + '" title="Eliminar Fila" style="cursor: pointer"></span></td><input type="hidden" name="re_actual[]" value="' + contador + '"><td><select name="re_tipo[' + contador + ']" id="re_tipo[' + contador + ']" class="customRequired"><option value="">Elija una opcion</option><option value="Inasistencia">Inasistencia</option><option value="Tarde">Llegada Tarde</option><option value="Retiro">Retiro Anticipado</option><option value="Licencia">Licencia</option></select></td><td><input type="text" id="re_docente[' + contador + ']" name="re_docente[' + contador + ']" style="width:220px" class="customRequired"><input type="hidden" id="re_doc_id[' + contador + ']" name="re_doc_id[' + contador + ']"></td><td><select name="re_motivo[' + contador + ']" id="re_motivo[' + contador + ']" class="customRequired" style="width:200px;"><option value="">Elija un motivo</option></select></td><td><input type="text" name="re_cargo[' + contador + ']" id="re_cargo[' + contador + ']" disabled="true" maxlength="5" size="2"></td><td><input type="text" maxlength="4" style="width:30px" name="re_horas[' + contador + ']" id="re_horas[' + contador + ']" class="customRequired"></td><td><input type="text" name="re_revista[' + contador + ']" id="re_revista[' + contador + ']" disabled="true"></td><input type="hidden" name="re_observacion[]" id="re_observacion[]" ></td></tr>'
					,'<tr id="' + trId + '"><td><span class="ui-icon ui-icon-close deleteRow" id="delete-' + trId + '" title="Eliminar Fila" style="cursor: pointer"></span></td><input type="hidden" name="acc_actual[]" value="' + contador + '"><td><select name="acc_tipo[' + contador + ']" id="acc_tipo[' + contador + ']" class="customRequired"><option value="">Seleccione un tipo</option><option value="Alumno">Alumno</option><option value="Profesor">Profesor</option></select></td><td><input type="text" name="acc_docente[' + contador + ']" id="acc_docente[' + contador + ']" style="width:220px" class="customRequired"><input type="hidden" name="acc_doc_id[' + contador + ']" id="acc_doc_id[' + contador + ']" class="customRequired"></td><td><input type="checkbox" name="acc_reporte_art[' + contador + ']" value="1" id="acc_reporte_art[' + contador + ']" /></td><td><textarea name="acc_descripcion[' + contador + ']" id="acc_descripcion[' + contador + ']" style="height:20px; width:450px" class="customRequired"></textarea></td></tr>'
					,'<tr id="' + trId + '"><td><span class="ui-icon ui-icon-close deleteRow" id="delete-' + trId + '" title="Eliminar Fila" style="cursor: pointer"></span></td><input type="hidden" name="he_actual[]" value="' + contador + '"><td><input type="text" name="he_docente[' + contador + ']" id="he_docente[' + contador + ']" class="customRequired"><input type="hidden" name="he_doc_id[' + contador + ']" id="he_doc_id[' + contador + ']"></td><td><input type="text" name="he_cantidad_horas[' + contador + ']" id="he_cantidad_horas[' + contador + ']" maxlength="2" size="2" class="customRequired"></td><td><textarea name="he_descripcion[' + contador + ']" id="he_descripcion[' + contador + ']" style="height:20px"  class="customRequired"></textarea></td></tr>'];
	
 	var selector = tableId + " > tbody ";
 	
 	
 	$(selector).append(trHtml[trNumber]);
	
	// agrego la posibilidad de eliminar la fila actual
	deleteRowsOnClick("deleteRow");
	
 	// 	agrego el dialog para las fechas
 	if (trNumber == 0){
		// agrego los motivos
		loadMotivos(trId + " select[name*='re_motivo']");
 		$("select[name^='re_motivo']").change(function(){
			if( $(this).attr("value") != "-1"){
				$("#span_" + trId).remove();
			} else {
 				// es una licencia, agrego la ventanita
 				$("#" + trId + " td:last").append("<span style='float:right;cursor:pointer' id='span_" + trId + "' class='ui-icon ui-icon-newwin'></span>");
 				// agrego el efecto onclick de la ventanita
 				// agrego, oculto, el div con dialog
 				var dialogHtml = "<div class='noShow' id='dialog_" + trId + "'><label for='licenciaFechaIni"+ trId + "'>Inicio Licencia: </label><input type='text' name='licenciaFechaIni"+ trId + "' id='licenciaFechaIni"+ trId + "'  /> <br/> <label for='licenciaFechaFin"+ trId + "'>Fin Licencia: </label><input type='text' name='licenciaFechaFin"+ trId + "' id='licenciaFechaFin"+ trId + "'  /></div>";
 				$("#"+trId).append(dialogHtml);
				$("#dialog_" + trId + " input[name*='licenciaFecha']").each(function(){
					$(this).datepicker();
				})
 				$("#span_" + trId).click(function(){
 					// opciones para el dialog -> debe poder, al hacer click, agregar un input en 
 					// el formulario con un ID dada para poder enviarlo a la base de datos
 					$("#dialog_" + trId).dialog({
 						title: "Fechas de la licencia",
 						buttons: {
 							Aceptar: function(){
 								// proceso para mezclar los dos inputs de la fecha, y ponerlos juntos
 								// en un mismo input, que ir�a a un campo opcional de motivos
								var texto = "Fechas de la licencia: ";
								$(this).children("input[name*='licenciaFecha']").each(function(){
									// concateno los dos caracteres y lo pongo en las observaciones, hidden
									
									texto += $(this).attr("value") + " - ";
								})
								$("#" + trId + " input[name*='re_observacion']").attr("value", texto);

 								// agrego un input con valor del valor procesado
 								$(this).dialog("close");
								$(this).remove();
 							}
 						}
 					});
 				})
 			}
 		}) ;
 	}
	
	suggest("#" + trId, prefix, "");
	// agrevo el evento onclick, a ver si se sobre escribe
	var tipo = $("#" + trId + " > td > select[name^='acc_tipo']"); 
	tipo.change(function(){
		var urlAux = "&filtro=" + tipo.val();
		suggest("#" + trId, prefix, urlAux );
	})
	
 }
 
 function deleteRowsOnClick(clase){
 	
	$("." + clase).each(function(){
		$(this).click(function(){
			var datos = $(this).attr("id").split("-");
			$("#" + datos[1]).remove();
		})
	})
	
 }
 
 // autocomplete de docentes
 
 function suggest(trSelector, prefix, urlAux){
 	
	var raiz = trSelector + " > td >" ;
	var selector = $( raiz + " input[name^='" + prefix + "_docente']");
	
	var url = "index.php?action=listarDocentes" + urlAux;
	 	
	$(selector).autocomplete({
		minLength: 2,
		source: url,
		focus: function(event, ui){
			$(selector).val(ui.item.label);
			return false;
		},
		select: function(event, ui){
			$(selector).val(ui.item.label);
			
			$(raiz + "input[name^='" + prefix + "_doc_id']").val(ui.item.id);
                        if( ui.item.id == "957"){
                            // agrego una alerta de
                            var msj = "Nombre del personal externo temporal: ";
                            $(raiz + "textarea[name^='" + prefix + "_descripcion']").text(msj);
                        }
			$(raiz + "input[name^='" + prefix + "_cargo']").val(ui.item.addData.cargo);
			$(raiz + "input[name^='" + prefix + "_revista']").val(ui.item.addData.revista);
			
			return false;
			}
	}).data("autocomplete")._renderItem = function(ul, item){
		return $("<li></li>").data("item.autocomplete", item).append("<a>" + item.label + "<br><span class='desc'>" + item.desc + "</span></a>").appendTo(ul);
	};
	
}


// validacion y envio del formulario de reporte


function sendFormAjax(formSel, reglas, mensajes){
	
		$(formSel).validate({
			errorClass: "errorValidate",
			errorContainer: "#errores",
			submitHandler: function(form) {
				var parteInfo = $(form).serializeArray();
				$.get("adminReports.php?view=parteDetail", parteInfo, function(html){
					$("body").append(html);
					$("#parteDetail").dialog({
						width: 940,
						height: 600,
						title: "Resumen del Parte Diario",
						modal: true,
						buttons: {
							Confirmar: function(){
								$(this).dialog("close");
								//$(this).remove();
								// envio el formulario
								jQuery(form).ajaxSubmit({
									dataType: "json",
									beforeSubmit: showLoading,
									success: showResponse
								});
                                                                $(this).remove();
							},
							Modificar: function(){
								$(this).dialog("close");
								$(this).remove();
							}
						}
					});
				});	
			}
		});
		
		$.validator.addMethod("cRequired", $.validator.methods.required,
  "*");
		$.validator.addClassRules("customRequired", {cRequired: true});

}

function showLoading(formData, jqForm, options){
	
	jqForm.slideUp();
	$("#message").empty();
	$("#message").addClass("loading");
	$("#message").fadeIn();

}

function displayMessage(div, msj, clase){
	$(div).removeClass("loading").removeClass("error").addClass(clase).html(msj).fadeIn();
}

function showResponse(responseText, statusText, xhr, $form){

	if(responseText.code == 0){
		displayMessage("#message", responseText.status, "success");
	} else {
		displayMessage("#message", responseText.status, "error");
	}
}

function loadMotivos(divMotivos){
	
	$.post("index.php", {action: "listarMotivos"}, function(data){
		var options = "";
		for(var i=0; i < data.length; i++){
			options += "<option value='" + data[i] + "'>" + data[i] + "</option>";
		}
		$("#" + divMotivos).append(options);
	},"json")
}

