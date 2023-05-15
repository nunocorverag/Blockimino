<?php
require '../../config/config.php';
if (isset($_GET['id_proyecto']))
{
    $id_proyecto = $_GET['id_proyecto'];

    if(isset($_POST['resultado']))
    {
        if($_POST['resultado'] == true)
        {
            $id_usuario_loggeado = $_GET['id_usuario'];

            $query_obtener_info_proyecto = mysqli_query($con, "SELECT * FROM proyectos WHERE id_proyecto='$id_proyecto' AND id_usuario_proyecto='$id_usuario_loggeado'");
            $fila = mysqli_fetch_array($query_obtener_info_proyecto);
            $link_proyecto = "../../" . $fila['link_proyecto'];
            $nombre = $fila['nombre_proyecto'];

            // Eliminar el archivo asociado al proyecto
            // $ unlink() -> Elimina un archivo alojado en el servidor
            unlink($link_proyecto);

            $query_eliminar_proyecto = mysqli_query($con, "DELETE FROM proyectos WHERE id_proyecto='$id_proyecto'");
        }
    }
}
?>