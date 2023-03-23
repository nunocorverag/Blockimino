<!-- Esta pagina se encargara de eliminar el post -->
<?php
require '../../config/config.php';

if (isset($_GET['id_publicacion']))
{
    $id_publicacion = $_GET['id_publicacion'];

    if(isset($_POST['resultado']))
    {
        if($_POST['resultado'] == true)
        {
            $es_propia = ($_POST['es_propia'] == true);
            $razon = '';
            if (!$es_propia && isset($_POST['razon']))
            {
                $razon = $_POST['razon'];
            }
            else
            {
                $razon = "Eliminado por el usuario";
            }

            $id_usuario_loggeado = $_GET['id_usuario'];

            $query_eliminar_post = mysqli_query($con, "UPDATE publicaciones SET borrado='si' WHERE id_publicacion='$id_publicacion'");
            $query_obtener_id_usuario = mysqli_query($con, "SELECT publicado_por FROM publicaciones WHERE id_publicacion='$id_publicacion'");
            $fila = mysqli_fetch_array($query_obtener_id_usuario);
            $id_usuario_publicacion_eliminada = $fila['publicado_por'];
            $query_guardar_log = mysqli_query($con, "INSERT INTO publicaciones_eliminadas VALUES ('', '$id_usuario_loggeado', '$id_publicacion', '$id_usuario_publicacion_eliminada', '$razon')");
        }
    }
}
?>