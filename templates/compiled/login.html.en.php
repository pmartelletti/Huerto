<link href="css/login.css" type="text/css" rel="stylesheet">
<script type="text/javascript">
	$(document).ready(function(){
		$("#sec_nombre").select();
		$("#sendLogin").button();
	})
</script>
<script type="text/javascript" src="js/login.js"></script>
<div id="wrap" class="container">
	<div class="span-6">&nbsp</div>
	<div id="login" class="span-11 last">
		<form id="formLogin" action="login.php" method="post">
			<div>
				<label for="sec_login">Seccion: </label>
				<?php echo $this->elements['sec_login']->toHtml();?>
			</div>
			<div>
				<label for="us_pass">Contrasena: </label>
				<?php echo $this->elements['us_pass']->toHtml();?>
			</div>
			<div>
				<?php echo $this->elements['action']->toHtml();?>
				<?php echo $this->elements['sendLogin']->toHtml();?>
			</div>
		</form>
		<div id="message" class="ui-corner-all centrado"></div>
	</div>
</div>
