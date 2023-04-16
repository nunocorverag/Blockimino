<?php
require '../../config/config.php';

if (isset($_GET['id_grupo']) && isset($_GET['id_usuario_propietario']))
{
    $id_grupo = $_GET['id_grupo'];
    $id_usuario_propietario = $_GET['id_usuario_propietario'];

    if(isset($_POST['resultado']))
    {
        if($_POST['resultado'] == true)
        {
            $query_seleccionar_publicaciones_grupo = mysqli_query($con, "SELECT id_publicacion, publicado_por FROM publicaciones WHERE (id_grupo_publicacion='$id_grupo' AND borrado='no')");
            if(mysqli_num_rows($query_seleccionar_publicaciones_grupo) > 0)
            {
                while($fila_publicacion = mysqli_fetch_array($query_seleccionar_publicaciones_grupo))
                {
                    //+ Guardar el log de la publicacion eliminada
                    $id_publicacion = $fila_publicacion['id_publicacion'];
                    $publicado_por = $fila_publicacion['publicado_por'];
                    $razon_eliminacion_publicacion = "El grupo fue eliminado";
                    $query_guardar_log = mysqli_query($con, "INSERT INTO publicaciones_eliminadas VALUES ('', '$id_usuario_propietario', '$id_publicacion', '$publicado_por', '$razon_eliminacion_publicacion')");
    
    
                    //+ Vamos a tomar cada comentario para meterlo en el log de comentarios eliminados
                    $query_obtener_comentarios = mysqli_query($con, "SELECT id_comentario, comentado_por FROM comentarios WHERE (publicacion_comentada='$id_publicacion' AND eliminado='no')");
    
                    //+ Borrar los comentarios de cada publicacion hecha en el grupo
                    if(mysqli_num_rows($query_obtener_comentarios) > 0)
                    {
                        while($fila_comentario = mysqli_fetch_array($query_obtener_comentarios))
                        {
                            $id_comentario = $fila_comentario['id_comentario'];
                            $comentado_por = $fila_comentario['comentado_por'];
                            $razon_eliminacion_comentario = "El grupo fue eliminado";
                            $query_guardar_log = mysqli_query($con, "INSERT INTO comentarios_eliminados VALUES ('', '$id_usuario_propietario', '$id_comentario', '$id_publicacion', '$comentado_por', '$razon_eliminacion_comentario')");
                        }
                        // + Establecemos todos los comentarios de la publicacion como eliminados
                        $query_eliminar_comentarios = mysqli_query($con, "UPDATE comentarios SET eliminado='si' WHERE publicacion_comentada='$id_publicacion'");
                    }
                }
            }

            // + Establecemos todas las publicaciones del grupo como eliminadas
            $query_eliminar_post = mysqli_query($con, "UPDATE publicaciones SET borrado='si' WHERE id_grupo_publicacion='$id_grupo'");

            // + Eliminamos el grupo de todos los usuarios a los que pertenezca
            $query_obtener_miembros_grupo = mysqli_query($con, "SELECT miembros_grupo FROM grupos WHERE id_grupo='$id_grupo'");
            $fila_miembros_grupo = mysqli_fetch_array($query_obtener_miembros_grupo);
            $lista_miembros = $fila_miembros_grupo['miembros_grupo'];
            $lista_miembros_explode = explode(",", $lista_miembros);
            $lista_miembros_explode = array_filter($lista_miembros_explode);

            foreach($lista_miembros_explode as $id_miembro)
            {
                $query_obtener_lista_grupos_miembro = mysqli_query($con, "SELECT lista_grupos FROM usuarios WHERE id_usuario='$id_miembro'");
                $fila_grupos_usuario = mysqli_fetch_array($query_obtener_lista_grupos_miembro);
                $lista_grupos_usuario = $fila_grupos_usuario['lista_grupos'];
                $nueva_lista_de_grupos_usuario = str_replace("," . $id_grupo . ",", ",", $lista_grupos_usuario);
                $query_eliminar_grupo_lista_grupos = mysqli_query($con, "UPDATE usuarios SET lista_grupos='$nueva_lista_de_grupos_usuario' WHERE id_usuario='$id_miembro'");    
            }

            // + Eliminamos los miembros del grupo
            $query_eliminar_miembros_grupo = mysqli_query($con, "UPDATE grupos SET miembros_grupo=',' WHERE id_grupo='$id_grupo'");

            //+ Eliminar las invitaciones del grupo 
            $query_eliminar_invitaciones_del_grupo = mysqli_query($con, "DELETE FROM invitaciones_de_grupo WHERE (id_grupo_invitado='$id_grupo')");
            //+ Eliminar las solicitudes del grupo
            $query_eliminar_solicitudes_del_grupo = mysqli_query($con, "DELETE FROM solicitudes_de_grupo WHERE (grupo_solicitado='$id_grupo')");

            //+ Cambiare el nombre del grupo por Eliminado(nombre_grupo) para que se pueda crear otro grupo con ese nombre
            $query_obtener_nombre_grupo = mysqli_query($con, "SELECT nombre_grupo FROM grupos WHERE id_grupo='$id_grupo'");
            $fila_nombre_grupo = mysqli_fetch_array($query_obtener_nombre_grupo);
            $nombre_grupo = "Eliminado(" . $fila_nombre_grupo['nombre_grupo'] . ")";
            // + Actualizamos el nombre del grupo
            $query_actualizar_nombre_grupo = mysqli_query($con, "UPDATE grupos SET nombre_grupo='$nombre_grupo' WHERE id_grupo='$id_grupo'");

            // + Establecemos el grupo como eliminado
            $query_eliminar_grupo = mysqli_query($con, "UPDATE grupos SET grupo_eliminado='si' WHERE id_grupo='$id_grupo'");
        }
    }
}
?>