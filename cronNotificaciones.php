<?php

require_once 'classes/NotificacionesController.php';
require_once 'classes/DbConfig.class.php';

DbConfig::setup();

$controller = new NotificacionesController();
$controller->requestAsyncAction("crearNotificacionesDiarias");


?>