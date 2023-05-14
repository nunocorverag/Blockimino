<?php
include("includes//header.php");
include("includes/form_handlers/settings_handler.php");
?>

<div class="columna_principal">
    <h4>Configuracion de cuenta</h4>
    <?php
    echo "<img src='" . $fila_detalles_usuario['foto_perfil']. "' class='fotoPerfilSettings'>";
    ?>
    <br>
    <br>
    <br>
    <a href="upload.php">Cambiar foto de perfil</a><br><br><br><br>

    Modifica los valores y da click en "Actualizar"

    <h4>Actualizar Información</h4>
    <form action="settings.php" method="POST">
        <?php 
        $query_informacion_usuario = mysqli_query($con, "SELECT nombre, apeP, apeM, email, username, activar_notificaciones, mostrar_proyectos FROM usuarios WHERE id_usuario='$id_usuario_loggeado'");
        $fila = mysqli_fetch_array($query_informacion_usuario);
        $nombre = $fila['nombre'];
        $apeP = $fila['apeP'];
        $apeM = $fila['apeM'];
        $email = $fila['email'];
        $username = $fila['username'];
        $notificaciones = $fila['activar_notificaciones'];
        $mostrar_proyectos = $fila['mostrar_proyectos'];
        ?>
        Primer nombre: <input type="text" name="nombre" value="<?php echo $nombre; ?>" class="input_settings">
        <br>
        <div class="register_error_message">
            <?php
                // $ in_array -> Checa si el valor existe en el arreglo
                if(in_array("Error: El nombre no puede contener simbolos!<br>", $error_array_info))
                {
                    echo "Error: El nombre no puede contener simbolos!<br>";
                }
                if(in_array("Error: El nombre debe de contener entre 2 y 50 caracteres!<br>", $error_array_info))
                {
                    echo "Error: El nombre debe de contener entre 2 y 50 caracteres!<br>";
                }
                if(in_array("Error: El nombre no puede contener numeros!<br>", $error_array_info))
                {
                    echo "Error: El nombre no puede contener numeros!<br>";
                }
            ?>
        </div>
        Apellido Paterno: <input type="text" name="apeP" value="<?php echo $apeP; ?>" class="input_settings">
        <br>
        <div class="register_error_message">
            <?php
                if(in_array("Error: El apellido paterno no puede contener simbolos!<br>", $error_array_info))
                {
                    echo "Error: El apellido paterno no puede contener simbolos!<br>";
                }
                if(in_array("Error: El apellido paterno debe de contener entre 2 y 50 caracteres!<br>", $error_array_info))
                {
                    echo "Error: El apellido paterno debe de contener entre 2 y 50 caracteres!<br>";
                }
                if(in_array("Error: El apellido paterno no puede contener numeros!<br>", $error_array_info))
                {
                    echo "Error: El apellido paterno no puede contener numeros!<br>";
                }
            ?>
        </div>
        Apellido Materno: <input type="text" name="apeM" value="<?php echo $apeM; ?>" class="input_settings">
        <br>
        <div class="register_error_message">
            <?php
                if(in_array("Error: El apellido materno no puede contener simbolos!<br>", $error_array_info))
                {
                    echo "Error: El apellido materno no puede contener simbolos!<br>";
                }
                if(in_array("Error: El apellido materno debe de contener entre 2 y 50 caracteres!<br>", $error_array_info))
                {
                    echo "Error: El apellido materno debe de contener entre 2 y 50 caracteres!<br>";
                }
                if(in_array("Error: El apellido materno no puede contener numeros!<br>", $error_array_info))
                {
                    echo "Error: El apellido materno no puede contener numeros!<br>";
                }
            ?>
        </div>
        Email: <input type="text" name="email" value="<?php echo $email; ?>" class="input_settings">
        <br>
        <div class="register_error_message">
            <?php
                if(in_array("Error: El email no puede contener mas de 100 caracteres!<br>", $error_array_info))
                {
                    echo "Error: El email no puede contener mas de 100 caracteres!<br>";
                }
                if(in_array("Error: El email ya esta en uso!<br>", $error_array_info))
                {
                    echo "Error: El email ya esta en uso!<br>";
                }
                if(in_array("Error: El formato del email es incorrecto!<br>", $error_array_info))
                {
                    echo "Error: El formato del email es incorrecto!<br>";
                }
            ?>
        </div>
        Nombre de usuario: <input type="text" name="username" value="<?php echo $username; ?>" class="input_settings">
        <br>
        <div class="register_error_message">
            <?php
                if(in_array("Error: El nombre de usuario no puede contener simbolos!<br>", $error_array_info))
                {
                    echo "Error: El nombre de usuario no puede contener simbolos!<br>";
                }
                if(in_array("Error: El nombre de usuario debe de contener entre 8 y 12 caracteres!<br>", $error_array_info))
                {
                    echo "Error: El nombre de usuario debe de contener entre 8 y 12 caracteres!<br>";
                }
                if(in_array("Error: El nombre de usuario no puede contener numeros!<br>", $error_array_info))
                {
                    echo "Error: El nombre de usuario no puede contener numeros!<br>";
                }
                if(in_array("Error: El nombre de usuario ya existe!<br>", $error_array_info))
                {
                    echo "Error: El nombre de usuario ya existe!<br>";
                }
            ?>
        </div>
        <input type="submit" name="actualizar_informacion" id="guardar_informacion_actualizar" value="Actualizar Informacion" class="info submit_settings">
        <div class="register_successful_message">
        <?php
            if(in_array("<span style='color: #14c800;'>Información actualizada!<br><br>", $successful_array_info))
            {
                echo "<span style='color: #14c800;'>Información actualizada!<br><br>";
            }
        ?>
        </div>
        <br>
    </form>
    <h4>Cambiar contraseña</h4>
    <form action="settings.php" method="POST">
        Contraseña anterior: <input type="password" name="old_password" class="input_settings">
        <br>
        <div class="register_error_message">
            <?php
                if(in_array("Error: La contraseña es incorrecta, se requiere la contraseña antigua para cambiarla!<br>", $error_array_password))
                {
                    echo "Error: La contraseña es incorrecta, se requiere la contraseña antigua para cambiarla!<br>";
                }
            ?>
        </div>
        Nueva contraseña: <input type="password" name="new_password" class="input_settings">
        <br>
        <div class="register_error_message">
            <?php
                if(in_array("Error: La contraseña debe de contener más de 8 caracteres!<br>", $error_array_password))
                {
                    echo "Error: La contraseña debe de contener más de 8 caracteres!<br>";
                }
                if(in_array("Error: La contraseña debe contener al menos un numero!<br>", $error_array_password))
                {
                    echo "Error: La contraseña debe contener al menos un numero!<br>";
                }
                if(in_array("Error: La contraseña debe contener al menos un simbolo!<br>", $error_array_password))
                {
                    echo "Error: La contraseña debe contener al menos un simbolo!<br>";
                }
                if(in_array("Error: La contraseña debe contener al menos una mayuscula!<br>", $error_array_password))
                {
                    echo "Error: La contraseña debe contener al menos una mayuscula!<br>";
                }
                if(in_array("Error: La contraseña debe contener al menos una minuscula!<br>", $error_array_password))
                {
                    echo "Error: La contraseña debe contener al menos una minuscula!<br>";
                }
            ?>
        </div>
        Confirmar nueva contraseña: <input type="password" name="confirm_new_password" class="input_settings">
        <br>
        <div class="register_successful_message">
        <?php
            if(in_array("Error: Las contraseñas no coinciden!<br>", $error_array_password))
            {
                echo "Error: Las contraseñas no coinciden!<br>";
            }
        ?>
        </div>
        <input type="submit" name="actualizar_contra" id="guardar_informacion_contra" value="Actualizar Contraseña" class="info submit_settings">
        <div class="register_successful_message">
        <?php
            if(in_array("<span style='color: #14c800;'>La contraseña ha sido actualizada!<br><br>", $successful_array_password))
            {
                echo "<span style='color: #14c800;'>La contraseña ha sido actualizada!<br><br>";
            }
        ?>
        </div>
        <br>
    </form>

    <h4>Notificaciones</h4>
    Activa o desactiva las notificaciones
    <br>
    <?php if($notificaciones)
    {
        echo "Activadas";
    }
    else
    {
        echo "Desactivadas";
    }
    ?>
    <label class="switch">
        <input type="checkbox" name="actualizar_notificaciones" <?php if ($notificaciones == 1) echo "checked"; ?> onclick="actualizarNotificaciones()">
        <span class="slider round"></span>
    </label>

    <script>
        function actualizarNotificaciones() {
            var id_usuario_loggeado = '<?php echo $id_usuario_loggeado ?>';
            var valorCheckboxNotificaciones = $('input[name=actualizar_notificaciones]').is(':checked') ? 1 : 0; // Obtener el valor de la checkbox
            $.ajax({
                url: 'includes/handlers/ajax_update_notifications.php',
                type: 'POST',
                data: {notificaciones:valorCheckboxNotificaciones, id_usuario_loggeado:id_usuario_loggeado},
                success: function(data) {
                    location.reload();
                }
            });
        }
    </script>

    <h4>Publicaciones</h4>
    Al hacer privadas tus publicaciones, no permitiras que otros usuarios que no sean amigos, descarguen los proyectos
    <br>
    <?php if($mostrar_proyectos)
    {
        echo "Publicas";
    }
    else
    {
        echo "Privadas";
    }
    ?>
    <label class="switch">
        <input type="checkbox" name="actualizar_conf_publicaciones" <?php if ($mostrar_proyectos == 1) echo "checked"; ?> onclick="actualizarConfPublicaciones()">
        <span class="slider round"></span>
    </label>

    <script>
        function actualizarConfPublicaciones() {
            var id_usuario_loggeado = '<?php echo $id_usuario_loggeado ?>';
            var valorCheckboxConfPublicaciones = $('input[name=actualizar_conf_publicaciones]').is(':checked') ? 1 : 0; // Obtener el valor de la checkbox
            $.ajax({
                url: 'includes/handlers/ajax_update_posts_settings.php',
                type: 'POST',
                data: {mostrar_proyectos:valorCheckboxConfPublicaciones, id_usuario_loggeado:id_usuario_loggeado},
                success: function(data) {
                    location.reload();
                }
            });
        }
    </script>



    <h4>Cerrar Cuenta</h4>
    <form action="settings.php" method="POST">
        <input type="submit" name="cerrar_cuenta" id="cerrar_cuenta" value="Cerrar Cuenta" class="danger submit_settings">
    </form>

</div>