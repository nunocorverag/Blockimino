<?php
include("includes/header.php");

if(isset($_GET['nombre_grupo']))
{
    $nombre_grupo = $_GET['nombre_grupo'];
    $query_info_grupo = mysqli_query($con, "SELECT * FROM grupos WHERE nombre_grupo='$nombre_grupo'");
    $fila = mysqli_fetch_array($query_info_grupo);
    $id_grupo = $fila['id_grupo'];
    $objeto_grupo_usuario_loggeado = new Grupo($con, $id_usuario_loggeado);
}
?>

<div class="cuerpo_configuracion">
    <?php
    if ($objeto_grupo_usuario_loggeado->EsUsuarioPropietario($id_grupo))
    {
        ?>
            <a href="members">Ver Miembros</a>
            <a href="invite">Invitar miembros</a>
            <a href="requests">Ver solicitudes</a>
            <br>
            
        <?php
    }
    else if($objeto_grupo_usuario_loggeado->UsuarioPerteneceAlGrupo($id_grupo))
    {
        echo "No tiene los permisos necesarios para administrar este grupo!";
    }
    else
    {
        echo "No pertenece a este grupo!";
    }

    ?>
</div>
