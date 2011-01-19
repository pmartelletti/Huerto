<div class="contentBody">
	<h3>Aprobar partes</h3>
	<p>Los siguientes partes no fueron aprobados por el administrador todavía. Es necesaria una previa aprobación del 
	administrador general para que los datos sean tomados como vlidos para los reportes del sistema y la contabilidad de la institución. </p>
	<table id="partesPendientes">
		<thead>
			<th>Id</th>
			<th>Sección</th>
			<th>Emitido por</th>
			<th>Fecha emisión</th>
		</thead>
		<tbody>
			<?php if ($this->options['strict'] || (is_array($t->partes)  || is_object($t->partes))) foreach($t->partes as $clave => $parte) {?><tr style="text-transform:capitalize; cursor:pointer;" id="par_<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($parte,"par_id"));?>">
				<td><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($parte,"par_id"));?></td>
				<td><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($parte,"_par_sec_id.sec_nombre"));?></td>
				<td><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($parte,"_par_us_id.us_nombre"));?></td>
				<td><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($parte,"par_fecha"));?></td>
			</tr><?php }?>
		</tbody>
	</table>
</div>