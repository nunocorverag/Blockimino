<?php
include("../../config/config.php");
include("../classes/Usuario.php");
include("../classes/Mensaje.php");

// - Limite de mensajes a cargar
$limite = 7;

$mensaje = new Mensaje($con, $_REQUEST['id_usuario_loggeado']);
?>
    <div class="ir_a_mis_conversaciones">
        <a href="messages.php">Ir a mis conversaciones</a>
    </div>
<?php
echo $mensaje->obtenerDropdownConversaciones($_REQUEST, $limite);

?>