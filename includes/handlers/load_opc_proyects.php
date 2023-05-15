<?php
include("../../config/config.php");

$id_usuario_loggeado = $_REQUEST['id_usuario_loggeado'];
$query_obtener_nombre_usuario = mysqli_query($con, "SELECT username FROM usuarios WHERE id_usuario='$id_usuario_loggeado'");
$fila_nombre_usuario = mysqli_fetch_array($query_obtener_nombre_usuario);
$nombre_usuario = $fila_nombre_usuario['username'];
?>
<div class="contenedor_botones_proyecto">
    <a href="<?php echo dirname($_SERVER['PHP_SELF']) ?>/../../<?php echo $nombre_usuario ?>/projects">
        <button>Ver proyectos</button>
    </a>
    <br>
    <a href="<?php echo dirname($_SERVER['PHP_SELF']) ?>/../../upload_project.php">
        <button>Subir proyecto</button>
    </a>
    <br>
    <a href="<?php echo dirname($_SERVER['PHP_SELF']) ?>/../../new_proyect.php">
        <button>Crear proyecto</button>
    </a>
</div>