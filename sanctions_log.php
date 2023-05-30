<?php
include("includes/header.php");

$query_comprobar_usuario_moderador_o_administrador = mysqli_query($con, "SELECT * FROM usuarios WHERE (id_usuario='$id_usuario_loggeado' AND (tipo='moderador' OR tipo='administrador'))");
if((mysqli_num_rows($query_comprobar_usuario_moderador_o_administrador) == 0))
{
    header("Location: home.php");
}

// u -> usuarios
// c -> sanciones

$query_seleccionar_info_sancion = mysqli_query($con,   "SELECT s.id_sancion, s.razon_sancion, s.tipo_sancion, s.fecha_sancion, s.sancion_eliminada, u1.username, 
                                                        u2.username as usuario_que_sanciono, s.id_publicacion_sancion, s.id_comentario_sancion
                                                        FROM sanciones s 
                                                        JOIN usuarios u1 ON s.id_usuario_sancionado = u1.id_usuario 
                                                        LEFT JOIN usuarios u2 ON s.id_usuario_que_sanciono = u2.id_usuario 
                                                        ORDER BY id_sancion DESC");

?>
    <div class="tabla_log_de_eliminacion">
        <table class="table table-striped table-bordered table-hover">
            <caption>
                <h2>Log de sanciones</h2>
            </caption>
            <thead class="table-dark">
                <th>ID</th>
                <th>Razón de la sanción</th>
                <th>Tipo</th>
                <th>Fecha expiración</th>
                <th>Usuario sancionado</th>
                <th>Usuario que sancionó</th>
                <th>Publicación sancionada</th>
                <th>Comentario sancionado</th>
                <th>Sancion eliminada</th>
            </thead>
            <tbody>
                <?php while($fila = mysqli_fetch_array($query_seleccionar_info_sancion)) 
                { ?>
                    <tr>
                        <td><?php echo $fila['id_sancion']; ?></td>
                        <td style="width: 20%;"><?php echo $fila['razon_sancion']; ?></td>
                        <td><?php echo $fila['tipo_sancion']; ?></td>
                        <?php
                        if($fila['tipo_sancion'] == "permanente")
                        {
                            ?>
                            <td><?php echo "Nunca"?></td>
                            <?php
                        }
                        else if($fila['tipo_sancion'] == "temporal")
                        {
                            ?>
                            <td><?php echo $fila['fecha_sancion']; ?></td>
                            <?php
                        }
                        ?>
                        <td><a href="<?php echo $fila['username']; ?>"> <?php echo $fila['username']; ?></a></td>
                        <?php
                        if($fila['usuario_que_sanciono'] == NULL)
                        {
                            ?>
                            <td><?php echo "SISTEMA"?></td>
                            <?php
                        }
                        else if($fila['usuario_que_sanciono'] != NULL)
                        {
                            ?>
                            <td><a href="<?php echo $fila['usuario_que_sanciono']; ?>"> <?php echo $fila['usuario_que_sanciono']; ?></a></td>
                            <?php
                        }
                        ?>
                        <td>
                            <a href="deleted_publication.php?id=<?php echo $fila['id_publicacion_sancion']?>">
                                <?php echo $fila['id_publicacion_sancion']; ?>
                            </a>
                        </td>
                        <td>
                        <a href="deleted_comment.php?id=<?php echo $fila['id_comentario_sancion']?>">
                                <?php echo $fila['id_comentario_sancion']; ?>
                            </a>
                        </td>
                        <td><?php echo $fila['sancion_eliminada']; ?></td>
                    </tr>
                <?php 
                } ?>
            </tbody>
        </table>
    </div>
</div>

<?php


?>