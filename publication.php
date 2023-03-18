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

?>


    <div class="columna_principal columna" id="columna_principal">
        <div class="area_publicaciones">
            <?php
                $publicacion = new Publicacion($con, $id_usuario_loggeado);
                $publicacion->obtenerPublicacionSolicitada($id_publicacion);
            ?>
        </div>

    </div>
</div>