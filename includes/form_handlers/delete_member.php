<!-- Esta pagina se encargara de eliminar el post -->
<?php
require '../../config/config.php';

if (isset($_GET['id_grupo']) && isset($_GET['id_miembro']))
{
    $id_miembro = $_GET['id_miembro'];
    $id_grupo = $_GET['id_grupo'];

    if(isset($_POST['resultado']))
    {
        if($_POST['resultado'] == true)
        {    
            // TODO ELIMINAR EL MIMBRO DEL GRUPO Y EL GRUPO DE LOS USUARIOS
            //+ Obtenemos la lista de miembros del grupo
            $query_detalles_grupo = mysqli_query($con, "SELECT miembros_grupo FROM grupos WHERE id_grupo='$id_grupo'");
            $fila_detalles_grupo = mysqli_fetch_array($query_detalles_grupo);
            $lista_miembros_grupo = $fila_detalles_grupo['miembros_grupo'];

            //+ Obtenemos la lista de grupos del usuario
            $query_detalles_usuario_a_remover = mysqli_query($con, "SELECT lista_grupos FROM usuarios WHERE id_usuario='$id_miembro'");
            $fila_detalles_usuario_a_remover = mysqli_fetch_array($query_detalles_usuario_a_remover);
            $lista_grupos_usuario = $fila_detalles_usuario_a_remover['lista_grupos'];

            // + Removemos al usuario del grupo
            $nueva_lista_de_miembros_grupo = str_replace("," . $id_miembro . ",", ",", $lista_miembros_grupo);
            $query_eliminar_miembro = mysqli_query($con, "UPDATE grupos SET miembros_grupo='$nueva_lista_de_miembros_grupo' WHERE id_grupo='$id_grupo'");
    
            // + Removemos el grupo de la lista de grupos del usuario
            $nueva_lista_de_grupos_usuario = str_replace("," . $id_grupo . ",", ",", $lista_grupos_usuario);
            $query_eliminar_grupo_lista_grupos = mysqli_query($con, "UPDATE usuarios SET lista_grupos='$nueva_lista_de_grupos_usuario' WHERE id_usuario='$id_miembro'");
        }
    }
}
?>