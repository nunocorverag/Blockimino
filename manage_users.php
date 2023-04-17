<?php
include("includes/header.php");

$query_comprobar_usuario_administrador = mysqli_query($con, "SELECT * FROM usuarios WHERE (id_usuario='$id_usuario_loggeado' AND tipo='administrador')");
if((mysqli_num_rows($query_comprobar_usuario_administrador) == 0))
{
    header("Location: home.php");
}

// u -> usuarios
// c -> sanciones

$query_seleccionar_info_sancion = mysqli_query($con,   "SELECT *  FROM usuarios WHERE (tipo='normal' OR tipo='moderador') ORDER BY id_usuario DESC");

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
                            <td><button class="descender_a_normal danger" id="descender_a_normal<?php echo $fila['username'] ?>">Descender a usuario normal</button></td>
                            <script>
                                // + Script de descender moderador a usuario normal
                                $(document).ready(function(){
                                    $('#descender_a_normal<?php echo $fila['username']; ?>').on('click', function() {
                                        bootbox.confirm("¿Estas seguro que quieres descender este usuario a usuario normal", function(result) {
                                        $.post("includes/form_handlers/user_to_normal.php?id_usuario=<?php echo $fila['id_usuario'];?>", {resultado:result});
                                            if(result == true)
                                            {
                                                location.reload();
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
                            <td><button class="ascender_a_moderador success" id="ascender_a_moderador<?php echo $fila['username'] ?>">Ascender a moderador</button></td>
                            <script>
                                // + Script de ascender un usuario normal a moderador
                                $(document).ready(function(){
                                    $('#ascender_a_moderador<?php echo $fila['username']; ?>').on('click', function() {
                                        bootbox.confirm("¿Estas seguro que quieres ascender este usuario a moderador", function(result) {
                                        $.post("includes/form_handlers/user_to_moderator.php?id_usuario=<?php echo $fila['id_usuario'];?>", {resultado:result});
                                            if(result == true)
                                            {
                                                location.reload();
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

<?php


?>