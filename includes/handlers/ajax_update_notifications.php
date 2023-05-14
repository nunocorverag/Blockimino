<?php
require '../../config/config.php';
if(isset($_POST['notificaciones']))
{
    $notificaciones = $_POST['notificaciones'];
    $id_usuario_loggeado = $_POST['id_usuario_loggeado'];
    $query_actualizar_informacion = mysqli_query($con, "UPDATE usuarios SET activar_notificaciones=" . $notificaciones . " WHERE id_usuario='" . $id_usuario_loggeado . "'");
}
?>