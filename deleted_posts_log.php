<?php
include("includes/header.php");

if(!($_SESSION['tipo'] == "moderador" || $_SESSION['tipo'] == "administrador"))
{
    header("Location: home.php");
}

// pe -> publicaciones_eliminadas
// u1 -> usuarios que publicaron las publicaciones
// u2 -> usuarios que eliminaron las publicaciones
$query_seleccionar_info = mysqli_query($con, "SELECT pe.id_eliminacion_publicacion, pe.id_publicacion_eliminada, u1.username, u2.username 
                                                as usuario_eliminador, pe.razon_eliminacion_publicacion, pe.id_usuario_que_elimino_publicacion 
                                                FROM publicaciones_eliminadas pe 
                                                JOIN usuarios u1 ON pe.id_usuario_que_publico = u1.id_usuario 
                                                JOIN usuarios u2 ON pe.id_usuario_que_elimino_publicacion = u2.id_usuario 
                                                ORDER BY id_eliminacion_publicacion DESC");

?>
    <div class="tabla_log_de_eliminacion">
        <table class="table table-striped table-bordered table-hover">
            <caption>
                <h2>Log de publicaciones eliminadas</h2>
            </caption>
            <thead class="table-dark">
                <th>ID</th>
                <th>ID de publicacion eliminada</th>
                <th>Usuario que publicó</th>
                <th>Usuario que eliminó</th>
                <th>Motivo de eliminacion</th>
            </thead>
            <tbody>
                <?php while($fila = mysqli_fetch_array($query_seleccionar_info)) 
                { ?>
                    <tr>
                        <td><?php echo $fila['id_eliminacion_publicacion']; ?></td>
                        <td style="width: 20%;"><?php echo $fila['id_publicacion_eliminada']; ?></td>
                        <td><a href="<?php echo $fila['username']; ?>"> <?php echo $fila['username']; ?></a></td>
                        <td><a href="<?php echo $fila['usuario_eliminador']; ?>"> <?php echo $fila['usuario_eliminador']; ?></a></td>
                        <td><?php echo $fila['razon_eliminacion_publicacion']; ?></td>
                    </tr>
                <?php 
                } ?>
            </tbody>
        </table>
    </div>
</div>

<?php


?>