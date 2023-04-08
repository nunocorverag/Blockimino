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

            // + Decrementamos el numero de posts en 1
            $query_eliminar_post = mysqli_query($con, "UPDATE usuarios SET num_posts=num_posts-1 WHERE id_usuario='$id_usuario_publicacion_eliminada'");
            $query_guardar_log = mysqli_query($con, "INSERT INTO publicaciones_eliminadas VALUES ('', '$id_usuario_loggeado', '$id_publicacion', '$id_usuario_publicacion_eliminada', '$razon')");

            //+ Vamos a tomar cada comentario para meterlo en el log de comentarios eliminados, ya que la publicacion fue eliminada
            $query_obtener_comentarios = mysqli_query($con, "SELECT id_comentario, comentado_por FROM comentarios WHERE (publicacion_comentada='$id_publicacion' AND eliminado='no')");

            //+ Borrar los comentarios de cada publicacion hecha en el grupo
            if(mysqli_num_rows($query_obtener_comentarios) > 0)
            {
                while($fila_comentario = mysqli_fetch_array($query_obtener_comentarios))
                {
                    $id_comentario = $fila_comentario['id_comentario'];
                    $comentado_por = $fila_comentario['comentado_por'];
                    $razon_eliminacion_comentario = "La publicación fue eliminada";
                    $query_guardar_log = mysqli_query($con, "INSERT INTO comentarios_eliminados VALUES ('', '$id_usuario_loggeado', '$id_comentario', '$id_publicacion', '$comentado_por', '$razon_eliminacion_comentario')");
                }
                // + Establecemos todos los comentarios de la publicacion como eliminados
                $query_eliminar_comentarios = mysqli_query($con, "UPDATE comentarios SET eliminado='si' WHERE publicacion_comentada='$id_publicacion'");
            }
        }
    }
}
?>