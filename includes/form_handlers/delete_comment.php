<!-- Esta pagina se encargara de eliminar el comentario-->
<?php
require '../../config/config.php';

if (isset($_GET['id_comentario']))
{
    $id_comentario = $_GET['id_comentario'];

    if(isset($_POST['resultado']))
    {
        if($_POST['resultado'] == true)
        {
            $es_propio = ($_POST['es_propio'] == true);
            $razon = '';
            if (!$es_propio && isset($_POST['razon']))
            {
                $razon = $_POST['razon'];
            }
            else
            {
                $razon = "Eliminado por el usuario";
            }

            $id_usuario_loggeado = $_GET['id_usuario'];

            $query_eliminar_comentario = mysqli_query($con, "UPDATE comentarios SET eliminado='si' WHERE id_comentario='$id_comentario'");
            $query_obtener_datos_comentario = mysqli_query($con, "SELECT comentado_por, publicacion_comentada  FROM comentarios WHERE id_comentario='$id_comentario'");
            $fila = mysqli_fetch_array($query_obtener_datos_comentario);
            $id_usuario_comentario_eliminado = $fila['comentado_por'];
            $id_publicacion_comentada = $fila['publicacion_comentada'];

            $query_guardar_log = mysqli_query($con, "INSERT INTO comentarios_eliminados VALUES ('', '$id_usuario_loggeado', '$id_comentario', '$id_publicacion_comentada', '$id_usuario_comentario_eliminado', '$razon')");
        }
    }
}
?>