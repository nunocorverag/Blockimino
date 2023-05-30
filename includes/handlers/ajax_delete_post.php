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
            $query_obtener_info_post = mysqli_query($con, "SELECT publicado_por, hashtags_publicacion FROM publicaciones WHERE id_publicacion='$id_publicacion'");
            $fila = mysqli_fetch_array($query_obtener_info_post);
            $id_usuario_publicacion_eliminada = $fila['publicado_por'];

            // + Decrementamos el numero de posts en 1
            $query_decrementar_post = mysqli_query($con, "UPDATE usuarios SET num_posts=num_posts-1 WHERE id_usuario='$id_usuario_publicacion_eliminada'");
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

            $hashtags = $fila['hashtags_publicacion'];
            if($hashtags != ",")
            {
                $lista_hashtags_explode = explode(",", $hashtags);
                $lista_hashtags_explode = array_filter($lista_hashtags_explode);

                foreach($lista_hashtags_explode as $hashtag)
                {
                    $query_info_hashtag = mysqli_query($con, "SELECT publicaciones_con_este_hashtag FROM hashtags WHERE id_hashtag='$hashtag'");
                    $fila_info_hashtag = mysqli_fetch_array($query_info_hashtag);
                    $publicaciones_con_este_hashtag = $fila_info_hashtag['publicaciones_con_este_hashtag'];

                            // + Removemos al amigo del usuario loggeado
                    $nueva_lista_de_publicaciones_hashtag = str_replace("," . $id_publicacion . ",", ",", $publicaciones_con_este_hashtag);
                    $query_eliminar_hashtag_publicacion = mysqli_query($con, "UPDATE hashtags SET publicaciones_con_este_hashtag='$nueva_lista_de_publicaciones_hashtag' WHERE id_hashtag='$hashtag'");

                    if($nueva_lista_de_publicaciones_hashtag == ",")
                    {
                        $query_eliminar_hashtag = mysqli_query($con, "DELETE FROM hashtags WHERE id_hashtag='$hashtag'");
                    }

                }
                $query_eliminar_hashtags_publicacion = mysqli_query($con, "UPDATE publicaciones SET hashtags_publicacion=',' WHERE id_publicacion='$id_publicacion'");
            }
        }
    }
}
?>