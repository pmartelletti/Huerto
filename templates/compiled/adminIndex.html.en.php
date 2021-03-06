<div id="contentBody">
       <script type="text/javascript" src="js/jquery-ui/js/jquery.blockUI.js"></script>
       <script type="text/javascript">
       		$(document).ajaxStart(function(){
			// bloqueo la interaccion del usuario para todas las peticiones ajax
			$.blockUI({
				theme:     true,
				title:    'Sistema de Partes - Huerto de los Olivos',
				message:  '<p>Cargando el contenido. Por favor, aguarde...</p>'
			});
		});
		
		$(document).ajaxStop(function(){
			// desbloqueo la interaccion con el usuario cuando todas las peticiones terminaron
			$.unblockUI();
		});
		
		$(document).ready(function(){
			$("#tabs").tabs({
				ajaxOptions: {
					error: function( xhr, status, index, anchor ){
						$( anchor.hash ).html("Couldn't load this tab. We'll try to fix this as soon as possible. If this wouldn't be a demo." );
						}
					}
				})
			})
	</script>
	
	<div id="tabs">
		<ul>
			<li><a href="notificaciones.php?view=notificacionesAdmin">Notificaciones</a></li>
			<li><a href="adminReports.php?view=tabs&tabView=aprobarPartes">Aprobar partes</a></li>
			<li><a href="adminReports.php?view=tabs&tabView=editarPartes">Editar partes</a></li>
			<li><a href="adminReports.php?view=tabs&tabView=editarDocentes">Editar Docentes / Alumnos</a></li>
			<li><a href="adminReports.php?view=tabs&tabView=informes">Informes</a></li>
			<li><a href="adminReports.php?view=tabs&tabView=ayuda">Ayuda</a></li>
			<li><a href="templates/sesion.html">Salir del sistema</a></li>
		</ul>
	</div>
</div>
