<div id="partePreview">
	<script type="text/javascript">
		$(document).ready(function(){
			$("#parteDetail input, #parteDetail select, #parteDetail textarea").attr("disabled", "disabled");
		})
	</script>
	<form id="parteDetail">
		
		<fieldset>
			<legend>Informacion General</legend>
		
			<p>
				<label for="par_id">Id parte:</label>
				<?php echo $this->elements['par_id']->toHtml();?>
				
				<label for="par_fecha">Fecha: </label>
				<?php echo $this->elements['par_fecha']->toHtml();?>
				
				<label for="par_sec_id">Seccion</label>
				<?php echo $this->elements['par_sec_id']->toHtml();?>
				
				<label for="par_us_id">Secretaria:</label>
				<?php echo $this->elements['par_us_id']->toHtml();?>
			</p>
			
			<p>
				<label for="par_observaciones">Observaciones generales del Administrador: </label><br />
				<?php echo $this->elements['par_observaciones']->toHtml();?>
			</p>
		
		</fieldset>
		
		<fieldset>
			<legend>Parte Diario</legend>
			<?php if ($this->options['strict'] || (is_array($t->partes)  || is_object($t->partes))) foreach($t->partes as $parte) {?><div>
				
				<p>
					<label for="re_tipo[]">Tipo</label>
					<input type="text" name="re_tipo[]" id="re_tipo[]" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($parte,"re_tipo"));?>">
				
					<label for="re_doc_id[]">Docente / alumno: </label>
					<input type="text" name="re_doc_id[]" id="re_doc_id[]" style="width:300px" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($parte,"_re_doc_id.doc_nombre"));?>">
					
				</p>
				<p>
					
					<label for="re_horas[]">Horas: </label>
					<input type="text" name="re_horas[]" id="re_horas[]" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($parte,"re_horas"));?>">
					
					<label for="re_motivo[]">Motivo: </label>
					<input type="text" name="re_motivo[]" id="re_motivo[]" style="width: 350px" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($parte,"re_motivo"));?>">
					
					<div style=" margin-right:20px">
						<label for="re_observacion[]">Observacion del Administrador</label><br />
						<textarea name="re_observacion[]" style="width:100%; height:40px" id="re_observacion[]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($parte,"re_observacion"));?></textarea>
					</div>
					
				</p>
				<div style="clear:both;border-bottom: 1px solid #cd0505; padding-bottom:10px; margin-bottom:10px"></div>
				
			</div><?php }?>
		</fieldset>
		<fieldset>
			<legend>Accidentes</legend>
			<?php if ($this->options['strict'] || (is_array($t->accidentes)  || is_object($t->accidentes))) foreach($t->accidentes as $accidente) {?><div style="clear:both;">
				
				<p>
					<label for="acc_tipo[]">Tipo</label>
					<input type="text" name="acc_tipo[]" id="acc_tipo[]" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($accidente,"acc_tipo"));?>">
				
					<label for="acc_doc_id[]">Docente / alumno: </label>
					<input type="text" name="acc_doc_id[]" id="acc_doc_id[]" style="width:300px" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getNombreAccidente'))) echo htmlspecialchars($t->getNombreAccidente($accidente));?>">
				</p>
				
				<p style="width:90%;">
					<div style="float:left;width:45%">
						<label for="acc_descripcion[]">Descripcion</label><br />
						<textarea name="acc_descripcion[]" style="height:80px" id="acc_descripcion[]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($accidente,"acc_descripcion"));?></textarea><br />
					</div>
					
					<div style="float:right; width:45%; margin-right:20px">
						<label for="acc_observacion[]">Observacion del Administrador</label><br />
						<textarea name="acc_observacion[]" style="height:80px" id="acc_observacion[]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($accidente,"acc_observacion"));?></textarea>
					</div>
					
				</p>
				<div style="clear:both;border-bottom: 1px solid #cd0505; padding-bottom:10px; margin-bottom:10px"></div>
				
			</div><?php }?>
		</fieldset>
		
		<fieldset>
			<legend>Horas Extras</legend>
			
			<?php if ($this->options['strict'] || (is_array($t->horasExtras)  || is_object($t->horasExtras))) foreach($t->horasExtras as $horas) {?><div style="clear:both;">
				<p>
					<label for="he_doc_id[]">Docente</label>
					<input type="text" name="he_doc_id[]" id="he_doc_id[]" style="width:300px" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($horas,"_he_doc_id.doc_nombre"));?>">
				
					<label for="he_cantidad_horas[]">Cantidad de Horas: </label>
					<input type="text" name="he_cantidad_horas[]" id="he_cantidad_horas[]" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($horas,"he_cantidad_horas"));?>">
				</p>
				
				<p style="width:90%;">
					<div style="float:left;width:45%">
						<label for="he_descripcion[]">Descripcion</label><br />
						<textarea name="he_descripcion[]" style="height:80px" id="he_descripcion[]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($horas,"he_descripcion"));?></textarea><br />
					</div>
					
					<div style="float:right; width:45%; margin-right:20px">
						<label for="he_observacion[]">Observacion del Administrador</label><br />
						<textarea name="he_observacion[]" style="height:80px" id="he_observacion[]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDBproperty'))) echo htmlspecialchars($t->getDBproperty($horas,"he_observacion"));?></textarea>
					</div>
					
				</p>
				<div style="clear:both;border-bottom: 1px solid #cd0505; padding-bottom:10px; margin-bottom:10px"></div>
				
			</div><?php }?>
			
		</fieldset>
	</form>
</div>