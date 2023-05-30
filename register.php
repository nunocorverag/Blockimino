<?php
require 'config/config.php';

// + Inicializar el arreglo de erroes para almacenar los errores (si es que register_handler regresa alguno)
$error_array = array(); // - Contendra los errores

require 'includes/form_handlers/register_handler.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <!-- Incluimos el archivo en donde diseñaremos nuestro css -->
    <link rel="stylesheet" href="assets/css/start_style.css">

    <style>
        html {
            height: 100%;
            background: linear-gradient(to bottom, #69c5b5, #336699, #154d24);
        }

        a {
            background-color: #2ecc71;
        }

        a:hover {
            background-color: #27ae60;
        }
    </style>

    <link rel="icon" href="assets/images/icons/blockimino.png">
</head>

<body>
    <!-- //RF6 Habra una pantalla especifica para crear cuentas -->
    <centerMargin>
    <boldDarkTitle>Registrarse en Blockimino</boldDarkTitle>

    <!-- Crearemos un formulario y lo mandaremos a esta misma pagina -->
    <form action="register.php" method="POST" class="formu">
        <!-- Registro del Nombre -->
        <input type="text" name="reg_nombre" placeholder="Primer nombre"
        value="<?php
        //Si ya existe un valor en el formulario: Lo reescribiremos en el mismo campo
            if(isset($_POST['reg_nombre']))
            {
                echo $_POST['reg_nombre'];
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
        if(isset($_POST['reg_apeP']))
            {
                echo $_POST['reg_apeP'];
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
        if(isset($_POST['reg_apeM']))
            {
                echo $_POST['reg_apeM'];
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
        if(isset($_POST['reg_email']))
            {
                echo $_POST['reg_email'];
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
        if(isset($_POST['reg_username']))
            {
                echo $_POST['reg_username'];
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
        if(isset($_POST['reg_password']))
            {
                echo $_POST['reg_password'];
            }
        ?>" required>
        <br>
        <div class="register_error_message">
            <?php
                if(in_array("Error: La contraseña debe de contener mas de 8 caracteres!<br>", $error_array))
                {
                    echo "Error: La contraseña debe de contener mas de 8 caracteres!<br>";
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
        if(isset($_POST['reg_confPassword']))
            {
                echo $_POST['reg_confPassword'];
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
        <centerMargin>
            <input type="submit" name="register_button" value="Registrarse">
        </centerMargin>
        <br>
    </form>

    <boldWhiteSlim>
    ¿Ya tienes una cuenta?
    </boldWhiteSlim>
    <a href="login.php">Inicia sesión aquí</a>
</centerMargin>
</body>
</html>