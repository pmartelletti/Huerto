<div class="contentBody">
	<h3>Aprobar partes</h3>
	<p>Los siguientes partes no fueron aprobados por el administrador todavía. Es necesaria una previa aprobación del 
	administrador general para que los datos sean tomados como vlidos para los reportes del sistema y la contabilidad de la institución. </p>
	<table id="partesPendientes">
		<thead>
			<th>Id</th>
			<th>Sección</th>
			<th>Emitido por</th>
			<th>Fecha emisión</th>			<th>Observaciones</th>
		</thead>
		<tbody>
			<?php if ($this->options['strict'] || (is_array($t->partes)  || is_object($t->partes))) foreach($t->partes as $clave => $parte) {?><tr style="text-transform:capitalize; cursor:pointer;" id="par_<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($parte,"par_id"));?>">
				<td><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($parte,"par_id"));?></td>
				<td><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($parte,"_par_sec_id.sec_nombre"));?></td>
				<td><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($parte,"_par_us_id.us_nombre"));?></td>
				<td><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($parte,"par_fecha"));?></td>				<td><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($parte,"par_observaciones"));?></td>
			</tr><?php }?>
		</tbody>
	</table>		<div id="msg" class="noShow">Hola</div>
</div><script>	$(document).ready(function(){		$("#partesPendientes tbody tr").each(function(){						$(this).hover(				function(){					// hoover in					$(this).css("background","#e5eeff")				},				function(){					// hoover out					$(this).css("background","white")				}			);						$(this).click(function(){				var fila = $(this);				var datos = $(this).attr("id").split("_");				var id = datos[1];				$.get("adminReports.php", {view: "parteDetail", idParte: id}, function(html){					$("body").append(html);					$("#partePreview").dialog({						width: 940,						height: 600,						title: "Resumen de Parte Diario",						modal: true,						buttons: {							Confirmar: function(){								// proceso los datos del id, apruebo el parte								$.post("adminReports.php", {action: "confirmaParte", idParte: id}, function(data){																		var divMsg = $("#msg");									divMsg.html(data.resMsg);									if(data.resCode == "200") divMsg.addClass("success");									else divMsg.addClass("error");									divMsg.fadeIn();																	}, "json");								$(this).dialog("close");								$(this).remove();								fila.remove();							},							"Continua Pendiente": function(){								// el parte no fue modificado								$(this).dialog("close");								$(this).remove();							}						}					});				});			})		})	});</script>
