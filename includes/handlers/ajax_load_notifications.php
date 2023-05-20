<?php
include("../../config/config.php");
include("../classes/Usuario.php");
include("../classes/Notificacion.php");

$id_usuario_loggeado = $_REQUEST['id_usuario_loggeado'];
$query_obtener_info_conf_notificaciones = mysqli_query($con, "SELECT activar_notificaciones, mostrar_proyectos FROM usuarios WHERE id_usuario='$id_usuario_loggeado'");
$fila_info_notificaciones = mysqli_fetch_array($query_obtener_info_conf_notificaciones);
$notificaciones = $fila_info_notificaciones['activar_notificaciones'];

if($notificaciones)
{
    // - Limite de notificaciones a cargar
    $limite = 7;

    $notificacion = new Notificacion($con, $_REQUEST['id_usuario_loggeado']);
    echo $notificacion->obtenerNotificaciones($_REQUEST, $limite);
}
else
{
    ?>
    <div style="text-align: center;" class="displayResultadoNotificacion">
        <p style="color: black;">Las notificaciones estan desactivadas!</p>
    </div>
    <?php
}
?>