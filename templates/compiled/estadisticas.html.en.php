<style>
	select {
		margin-right: 20px;
		margin-left: 5px;
	}
</style>
<meta content="text/html; charset=iso-8859-1">
<div>
	<?php echo $this->elements['graficos']->toHtmlnoClose();?>
		<fieldset>
			<legend>Graficos Estadisticos</legend>
			
			<p>
				<label for="gf_nombre">Grafico:</label>
				<?php echo $this->elements['gf_nombre']->toHtml();?>
				
				<label for="gf_subgrafico">Opciones1:</label>
				<?php echo $this->elements['gf_subgrafico']->toHtml();?>
				
				<label for="gf_opcionales">Opciones2:</label>
				<?php echo $this->elements['gf_opcionales']->toHtml();?>
			</p>
			
			<p>
				<label for="gf_filtro">Filtro</label>
				<?php echo $this->elements['gf_filtro']->toHtml();?>
				
				<label for="from">Desde:</label>
				<?php echo $this->elements['from']->toHtml();?>
				
				<label for="to">hasta:</label>
				<?php echo $this->elements['to']->toHtml();?>
			</p>
			
			<p>
				<label for="gf_titulo">Título del gráfico</label>
				<?php echo $this->elements['gf_titulo']->toHtml();?>
			</p>
			
			<p>
				<b>Nota 1:</b> En caso de querer hacer un grafico que junte a todas las secciones, 
			no seleccione ninguna en menu desplegable de arriba.<br />
				<b>Nota 2:</b> Puede personalizar más todavía ingresando un nombre para el gráfico. De ésta forma es más fácil de 
				copiar el gráfico para insertarlo en algún documento de texto, enviarlo por mail, etc.
			</p>
			
			<?php echo $this->elements['send']->toHtml();?>
			
		</fieldset>
	</form>
	
	<div id="grafico"></div>
	
	<script type="text/javascript">
		// script para llenar asincronicamente los graficos
		$(document).ready(function(){
			
			$("#send").click(function(){
				
				$.post("estadisticas.php?action=getGrafico", $("form").serializeArray(), function(response){
					$("#grafico").html(response);
				});
				
				
				return false;
			})
			
			$("#send").button();
			
			$("#gf_nombre").change(function(){
				var value = $(this).val();
					$.post("estadisticas.php", {action: "getOpcionesGraficos", gf_nombre: value}, function(response){
						createOptions("gf_subgrafico", response.subgrafico);
						createOptions("gf_opcionales", response.opcional);
						createOptions("gf_filtro", response.filtrar);
					}, "json");
			});
			
		})
		function createOptions(div, values){
			
			if(values == "") {
				$("#" + div).empty();
			} else {
				var opciones = "<option value=''>Elija una opcion</option>";
				values = values.split(",");
				for (var i = 0; i < values.length; i++) {
					claveValor = values[i].split(":");
					opciones += "<option value='" + claveValor[0] + "'>" + claveValor[1] + "</option>";
				}
				$("#" + div).html(opciones);
			}
		}
		
		$(function() {
			var dates = $( "#from, #to" ).datepicker({
				defaultDate: "+1w",
				changeMonth: true,
				numberOfMonths: 2,
				dateFormat: 'yy-mm-dd',
				onSelect: function( selectedDate ) {
					var option = this.id == "from" ? "minDate" : "maxDate",
						instance = $( this ).data( "datepicker" );
						date = $.datepicker.parseDate(
							instance.settings.dateFormat ||
							$.datepicker._defaults.dateFormat,
							selectedDate, instance.settings );
					dates.not( this ).datepicker( "option", option, date );
				}
			});
		});
		
	</script>
</div>