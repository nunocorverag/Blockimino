<?php
include("includes/header.php");
if(isset($_GET['id']))
{
    $id_publicacion = $_GET['id'];
}
else
{
    $id_publicacion = 0;
}

$query_comprobar_usuario_moderador_o_administrador = mysqli_query($con, "SELECT * FROM usuarios WHERE (id_usuario='$id_usuario_loggeado' AND (tipo='moderador' OR tipo='administrador'))");
if((mysqli_num_rows($query_comprobar_usuario_moderador_o_administrador) == 0))
{
    header("Location: home.php");
}

?>


    <div class="columna_principal columna" id="columna_principal">
        <div class="area_publicaciones">
            <?php
                $publicacion = new Publicacion($con, $id_usuario_loggeado);
                $publicacion->obtenerPublicacionEliminadaSolicitada($id_publicacion);
            ?>
        </div>

    </div>
</div>