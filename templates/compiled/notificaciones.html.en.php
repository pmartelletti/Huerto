<div id="contenedor">
	
	<script type="text/javascript">
		
		$(document).ready(function(){
			
			$(".close-notification").click(function(){
				var id = $(this).attr("id").split("_");
				var idNotificacion = id[1];
				eliminarNotificacion(idNotificacion);

			})
			
			function eliminarNotificacion(idNotificacion){
				// elimino la notificacion de la base de datos
				$.post("notificaciones.php", {action: "deleteNotificacion", idNot: idNotificacion}, function(response){
					
					if( response.statusCode == "100"){
						// elimino la notificacion del html
						$("#idNot_" + idNotificacion).remove();
					} else {
						// muestro un dialog diciendo que hubo un error
						var msg = "<p id='error_msg'>" + response.statusMsg + ". Por favor, intente nuevamente.</p>";
						$("body").append(msg);
						$("#error_msg").dialog({
							title: "Error al eliminar la notificacion",
							buttons: {
								Cerrar: function(){
									$(this).dialog("close");
									$(this).remove();
								}
							}
						})
					}
					
				}, "json");
				
				
			}			
			
		});
	</script>
	
	
	<h3>Notificaciones del sistema</h3>
	<div><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getNotificationsDisplay'))) echo htmlspecialchars($t->getNotificationsDisplay());?></div>
</div>