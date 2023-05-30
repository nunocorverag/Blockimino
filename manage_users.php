<?php
include("includes/header.php");

$query_comprobar_usuario_administrador = mysqli_query($con, "SELECT * FROM usuarios WHERE (id_usuario='$id_usuario_loggeado' AND tipo='administrador')");
if((mysqli_num_rows($query_comprobar_usuario_administrador) == 0))
{
    header("Location: home.php");
}

$query_seleccionar_info_sancion = mysqli_query($con, "SELECT *  FROM usuarios WHERE (tipo='normal' OR tipo='moderador') ORDER BY id_usuario DESC");

?>
    <div class="tabla_administrar_usuarios">
        <table class="table table-striped table-bordered table-hover">
            <caption>
                <h2>Administrar usuarios</h2>
            </caption>
            <thead class="table-dark">
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Nombre de usuario</th>
                <th>Tipo de usuario</th>
                <th>Opciones</th>
            </thead>
            <tbody>
                <?php while($fila = mysqli_fetch_array($query_seleccionar_info_sancion)) 
                { ?>
                    <tr>
                        <td><?php echo $fila['id_usuario']; ?></td>
                        <td style="width: 30%;"><?php echo $fila['nombre'] . " " . $fila['apeP'] . " " . $fila['apeM']?></td>
                        <td><?php echo $fila['email']; ?></td>
                        <td><?php echo $fila['username']; ?></td>
                        <td><?php echo $fila['tipo']; ?></td>
                        <?php
                        if($fila['tipo'] == "moderador")
                        {
                            ?>
                            <td style="width: 23%;"><button class="descender_a_normal danger" id="descender_a_normal<?php echo $fila['username'] ?>">Descender a usuario normal</button></td>
                            <script>
                            // Script de descender moderador a usuario normal
                            $(document).ready(function() {
                                $('#descender_a_normal<?php echo $fila['username']; ?>').on('click', function() {
                                    bootbox.confirm("¿Estás seguro de que quieres descender este usuario a usuario normal?", function(result) {
                                        if (result == true) {
                                            $.post("includes/handlers/ajax_user_to_normal.php?id_usuario=<?php echo $fila['id_usuario']; ?>", {
                                                resultado: result
                                            }, function() {
                                                location.href = 'manage_users.php'; // Aquí puedes especificar la URL de la página actual para redirigir a la misma página.
                                            });
                                        }
                                    });
                                });
                            });
                        </script>
                            <?php
                        }
                        else if($fila['tipo'] == "normal")
                        {
                            ?>
                            <td style="width: 23%;"><button class="ascender_a_moderador success" id="ascender_a_moderador<?php echo $fila['username'] ?>">Ascender a moderador</button></td>
                            <script>
                                // Script de ascender un usuario normal a moderador
                                $(document).ready(function(){
                                    $('#ascender_a_moderador<?php echo $fila['username']; ?>').on('click', function() {
                                        bootbox.confirm("¿Estás seguro de que quieres ascender este usuario a moderador?", function(result) {
                                            if (result == true) {
                                                $.post("includes/handlers/ajax_user_to_moderator.php?id_usuario=<?php echo $fila['id_usuario']; ?>", {
                                                    resultado: result
                                                }, function() {
                                                    location.href = 'manage_users.php'; // Aquí puedes especificar la URL de la página actual para redirigir a la misma página.
                                                });
                                            }
                                        });
                                    });
                                });
                            </script>
                            <?php
                        }
                        ?>
                    </tr>

                <?php 
                } ?>
            </tbody>
        </table>
    </div>
</div>