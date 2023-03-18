<?php
include("includes/header.php");

if(!($_SESSION['tipo'] == "moderador" || $_SESSION['tipo'] == "administrador"))
{
    header("Location: home.php");
}

// pe -> publicaciones_eliminadas
// u -> usuarios
$query_seleccionar_info = mysqli_query($con, "SELECT pe.id_eliminacion, pe.id_publicacion_eliminada, u.username, pe.razon_eliminacion FROM publicaciones_eliminadas pe JOIN usuarios u ON pe.id_usuario_que_publico = u.id_usuario");

?>
    <div class="tabla_log_publicaciones_eliminadas">
        <table class="table table-striped table-bordered table-hover">
            <caption>
                <h2>Log de publicaciones eliminadas</h2>
            </caption>
            <thead class="table-dark">
                <th>ID</th>
                <th>ID de publicacion eliminada</th>
                <th>Usuario</th>
                <th>Motivo de eliminacion</th>
            </thead>
            <tbody>
                <?php while($fila = mysqli_fetch_array($query_seleccionar_info)) 
                { ?>
                    <tr>
                        <td><?php echo $fila['id_eliminacion']; ?></td>
                        <td><?php echo $fila['id_publicacion_eliminada']; ?></td>
                        <td><a href="<?php echo $fila['username']; ?>"> <?php echo $fila['username']; ?></a></td>
                        <td><?php echo $fila['razon_eliminacion']; ?></td>
                    </tr>
                <?php 
                } ?>
            </tbody>
        </table>
    </div>
</div>

<?php


?>