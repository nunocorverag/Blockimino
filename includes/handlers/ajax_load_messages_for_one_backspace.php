<?php
include("../../config/config.php");
include("../classes/Usuario.php");
include("../classes/Mensaje.php");

// - Limite de mensajes a cargar
$limite = 7;

$mensaje = new Mensaje($con, $_REQUEST['id_usuario_loggeado']);
echo $mensaje->obtenerDropdownConversacionesUnBackspace($_REQUEST, $limite);

?>