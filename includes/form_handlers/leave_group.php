<!-- Esta pagina se encargara de salir del grupo-->
<?php
require '../../config/config.php';

if (isset($_GET['id_grupo']) && isset($_GET['id_usuario']))
{
    $id_grupo = $_GET['id_grupo'];
    $id_usuario = $_GET['id_usuario'];

    if(isset($_POST['resultado']))
    {
        if($_POST['resultado'] == true)
        {
            

            $nueva_lista_de_grupos_usuario = str_replace("," . $id_grupo . ",", ",", $lista_grupos_usuario);
            $query_eliminar_grupo_lista_grupos = mysqli_query($con, "UPDATE usuarios SET lista_grupos='$nueva_lista_de_grupos_usuario' WHERE id_usuario='$id_miembro'");    

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