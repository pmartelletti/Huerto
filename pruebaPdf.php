<?php

require_once 'classes/DbConfig.class.php';
require_once 'classes/Notificaciones.class.php';

DbConfig::setup();

$not = NotificacionesQuery::getInstancia(1);

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/blueprint/screen.css" type="text/css" rel="stylesheet">
<link href="css/general.css" type="text/css" rel="stylesheet">
<link href="js/jquery-ui/css/blitzer/jquery-ui-1.8.5.custom.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="js/jquery-ui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui/js/jquery-ui-1.8.5.custom.min.js"></script>
<script type="text/javascript" src="js/jquery-ui/js/jquery.form.js"></script>
<script type="text/javascript" src="js/jquery-ui/js/jquery.validate.js"></script>
<link rel="stylesheet" href="js/jquery-grid/css/ui.jqgrid.css" type="text/css">
<script type="text/javascript" src="js/general.js"></script>
<title>JHO - Administracion interna</title>
</head>
<body>
	<?php 
	
		echo $not->getHtml();
	
	?>
</body>
</html>
