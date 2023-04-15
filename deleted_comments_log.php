<?php
include("includes/header.php");

if(!($_SESSION['tipo'] == "moderador" || $_SESSION['tipo'] == "administrador"))
{
    header("Location: home.php");
}

// ce -> publicaciones_eliminadas
// u -> usuarios
$query_seleccionar_info = mysqli_query($con, "SELECT ce.id_eliminacion_comentario, ce.id_comentario_eliminado, u1.username, 
                                                u2.username as usuario_eliminador, ce.id_publicacion_comentario, ce.razon_eliminacion_comentario 
                                                FROM comentarios_eliminados ce 
                                                JOIN usuarios u1 ON ce.id_usuario_que_comento = u1.id_usuario 
                                                JOIN usuarios u2 ON ce.id_usuario_que_elimino_comentario = u2.id_usuario 
                                                ORDER BY id_eliminacion_comentario DESC");

?>
    <div class="tabla_log_de_eliminacion">
        <table class="table table-striped table-bordered table-hover">
            <caption>
                <h2>Log de comentarios eliminados</h2>
            </caption>
            <thead class="table-dark">
                <th>ID</th>
                <th>ID de comentario eliminado</th>
                <th>ID de publicacion del comentario</th>
                <th>Usuario que comentó</th>
                <th>Usuario que eliminó</th>
                <th>Motivo de eliminacion</th>
            </thead>
            <tbody>
                <?php while($fila = mysqli_fetch_array($query_seleccionar_info)) 
                { ?>
                    <tr>
                        <td><?php echo $fila['id_eliminacion_comentario']; ?></td>
                        <td style="width: 20%;"><?php echo $fila['id_comentario_eliminado']; ?></td>
                        <td style="width: 22%;"><?php echo $fila['id_publicacion_comentario']; ?></td>
                        <td><a href="<?php echo $fila['username']; ?>"> <?php echo $fila['username']; ?></a></td>
                        <td><a href="<?php echo $fila['usuario_eliminador']; ?>"> <?php echo $fila['usuario_eliminador']; ?></a></td>
                        <td><?php echo $fila['razon_eliminacion_comentario']; ?></td>
                    </tr>
                <?php 
                } ?>
            </tbody>
        </table>
    </div>
</div>

<?php


?>