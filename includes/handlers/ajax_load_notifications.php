<?php
include("../../config/config.php");
include("../classes/Usuario.php");
include("../classes/Notificacion.php");

// - Limite de notificaciones a cargar
$limite = 7;

$notificacion = new Notificacion($con, $_REQUEST['id_usuario_loggeado']);
echo $notificacion->obtenerNotificaciones($_REQUEST, $limite);

?>