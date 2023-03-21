<!-- En este archivo se podra registrar un nuevo usuario -->
<?php
//Utilizaremos el archivo config.php que tiene la conexion a nuestra base de datos
require 'config/config.php';
require 'includes/form_handlers/form_variables.php';
require 'includes/form_handlers/register_handler.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <!-- Incluimos el archivo en donde diseñaremos nuestro css -->
    <link rel="stylesheet" href="assets/css/start_style.css">
</head>

<body>
    <!-- //RF6 Habra una pantalla especifica para crear cuentas -->
    <h1>Registrarse en Blockimino</h1>
    <!-- Crearemos un formulario y lo mandaremos a esta misma pagina -->
    <form action="register.php" method="POST">
        <!-- Registro del Nombre -->
        <input type="text" name="reg_nombre" placeholder="Primer nombre"
        value="<?php
        //Si ya existe un valor en el formulario: Lo reescribiremos en el mismo campo
            if(isset($_SESSION['reg_nombre']))
            {
                echo $_SESSION['reg_nombre'];
            }
        ?>" required>
        <br>
        <div class="register_error_message">
            <?php
                // $ in_array -> Checa si el valor existe en el arreglo
                if(in_array("Error: El nombre no puede contener simbolos!<br>", $error_array))
                {
                    echo "Error: El nombre no puede contener simbolos!<br>";
                }
                if(in_array("Error: El nombre debe de contener entre 2 y 50 caracteres!<br>", $error_array))
                {
                    echo "Error: El nombre debe de contener entre 2 y 50 caracteres!<br>";
                }
                if(in_array("Error: El nombre no puede contener numeros!<br>", $error_array))
                {
                    echo "Error: El nombre no puede contener numeros!<br>";
                }
            ?>
        </div>
        <!-- Registro del apellido paterno -->
        <input type="text" name="reg_apeP" placeholder="Apellido Paterno"
        value="<?php
        if(isset($_SESSION['reg_apeP']))
            {
                echo $_SESSION['reg_apeP'];
            }
        ?>" required>
        <br>
        <div class="register_error_message">
            <?php
                if(in_array("Error: El apellido paterno no puede contener simbolos!<br>", $error_array))
                {
                    echo "Error: El apellido paterno no puede contener simbolos!<br>";
                }
                if(in_array("Error: El apellido paterno debe de contener entre 2 y 50 caracteres!<br>", $error_array))
                {
                    echo "Error: El apellido paterno debe de contener entre 2 y 50 caracteres!<br>";
                }
                if(in_array("Error: El apellido paterno no puede contener numeros!<br>", $error_array))
                {
                    echo "Error: El apellido paterno no puede contener numeros!<br>";
                }
            ?>
        </div>
        <!-- Registro del apellido materno -->
        <input type="text" name="reg_apeM" placeholder="Apellido Materno"
        value="<?php
        if(isset($_SESSION['reg_apeM']))
            {
                echo $_SESSION['reg_apeM'];
            }
        ?>" required>
        <br>
        <div class="register_error_message">
            <?php
                if(in_array("Error: El apellido materno no puede contener simbolos!<br>", $error_array))
                {
                    echo "Error: El apellido materno no puede contener simbolos!<br>";
                }
                if(in_array("Error: El apellido materno debe de contener entre 2 y 50 caracteres!<br>", $error_array))
                {
                    echo "Error: El apellido materno debe de contener entre 2 y 50 caracteres!<br>";
                }
                if(in_array("Error: El apellido materno no puede contener numeros!<br>", $error_array))
                {
                    echo "Error: El apellido materno no puede contener numeros!<br>";
                }
            ?>
        </div>
        <!-- Registro del email -->
        <input type="email" name="reg_email" placeholder="Email"
        value="<?php
        if(isset($_SESSION['reg_email']))
            {
                echo $_SESSION['reg_email'];
            }
        ?>" required>
        <br>
        <div class="register_error_message">
            <?php
                if(in_array("Error: El email no puede contener mas de 100 caracteres!<br>", $error_array))
                {
                    echo "Error: El email no puede contener mas de 100 caracteres!<br>";
                }
                if(in_array("Error: El email ya esta en uso!<br>", $error_array))
                {
                    echo "Error: El email ya esta en uso!<br>";
                }
                if(in_array("Error: El formato del email es incorrecto!<br>", $error_array))
                {
                    echo "Error: El formato del email es incorrecto!<br>";
                }
            ?>
        </div>
        <!-- //RF3 El usuario podra crear un usuario registrandose con un nombre de usuario y una contraseña -->
        <!-- Registro del nombre de usuario -->
        <input type="text" name="reg_username" placeholder="Nombre de Usuario"
        value="<?php
        if(isset($_SESSION['reg_username']))
            {
                echo $_SESSION['reg_username'];
            }
        ?>" required>
        <br>
        <div class="register_error_message">
            <?php
                if(in_array("Error: El nombre de usuario no puede contener simbolos!<br>", $error_array))
                {
                    echo "Error: El nombre de usuario no puede contener simbolos!<br>";
                }
                if(in_array("Error: El nombre de usuario debe de contener entre 8 y 12 caracteres!<br>", $error_array))
                {
                    echo "Error: El nombre de usuario debe de contener entre 8 y 12 caracteres!<br>";
                }
                if(in_array("Error: El nombre de usuario no puede contener numeros!<br>", $error_array))
                {
                    echo "Error: El nombre de usuario no puede contener numeros!<br>";
                }
                if(in_array("Error: El nombre de usuario ya existe!<br>", $error_array))
                {
                    echo "Error: El nombre de usuario ya existe!<br>";
                }
            ?>
        </div>
        <!-- Registro de la contraseña del usuario -->
        <input type="password" name="reg_password" placeholder="Contraseña"
        value="<?php
        if(isset($_SESSION['reg_password']))
            {
                echo $_SESSION['reg_password'];
            }
        ?>" required>
        <br>
        <div class="register_error_message">
            <?php
                if(in_array("Error: La contraseña debe de contener entre 8 y 12 caracteres!<br>", $error_array))
                {
                    echo "Error: La contraseña debe de contener entre 8 y 12 caracteres!<br>";
                }
                if(in_array("Error: La contraseña debe contener al menos un numero!<br>", $error_array))
                {
                    echo "Error: La contraseña debe contener al menos un numero!<br>";
                }
                if(in_array("Error: La contraseña debe contener al menos un simbolo!<br>", $error_array))
                {
                    echo "Error: La contraseña debe contener al menos un simbolo!<br>";
                }
                if(in_array("Error: La contraseña debe contener al menos una mayuscula!<br>", $error_array))
                {
                    echo "Error: La contraseña debe contener al menos una mayuscula!<br>";
                }
                if(in_array("Error: La contraseña debe contener al menos una minuscula!<br>", $error_array))
                {
                    echo "Error: La contraseña debe contener al menos una minuscula!<br>";
                }
            ?>
        </div>
        <!-- Confirmación de contraseña -->
        <input type="password" name="reg_confPassword" placeholder="Confirmar contraseña"
        value="<?php
        if(isset($_SESSION['reg_confPassword']))
            {
                echo $_SESSION['reg_confPassword'];
            }
        ?>" required>
        <br>
        <div class="register_error_message">
            <?php
                if(in_array("Error: Las contraseñas no coinciden!<br>", $error_array))
                {
                    echo "Error: Las contraseñas no coinciden!<br>";
                }
            ?>
        </div>
        <input type="submit" name="register_button" value="Register">
        <br>
        <div class="register_successful_message">
        <?php
            if(in_array("<span style='color: #14c800;'>¡Listo! ¡Puedes iniciar sesion!</span><br>", $successful_array))
            {
                echo "<span style='color: #14c800;'>¡Listo! ¡Puedes iniciar sesion!</span><br>";
            }
        ?>
        </div>
    </form>
    ¿Ya tienes una cuenta?<a href="login.php">Inicia sesión aquí</a>
</body>

</html>