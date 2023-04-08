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

<div class="cuerpo_miembros">
    <?php
    if ($objeto_grupo_usuario_loggeado->UsuarioPerteneceAlGrupo($id_grupo))
    {
        ?>
        <span>
            <h4>Lista de miembros de <?php echo $fila['nombre_grupo']; ?></h4>
        </span>
        <br>
        <?php
        $objeto_grupo_usuario_loggeado->DisplayMiembros($id_grupo);
    }
    else
    {
        echo "No pertenece a este grupo!";
    }

    ?>
</div>