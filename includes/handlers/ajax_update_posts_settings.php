<?php
require '../../config/config.php';
if(isset($_POST['mostrar_proyectos']))
{
    $mostrar_proyectos = $_POST['mostrar_proyectos'];
    $id_usuario_loggeado = $_POST['id_usuario_loggeado'];
    $query_actualizar_informacion = mysqli_query($con, "UPDATE usuarios SET mostrar_proyectos=" . $mostrar_proyectos . " WHERE id_usuario='" . $id_usuario_loggeado . "'");
}
?>