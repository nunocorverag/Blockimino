<?php
require 'config/config.php';
require 'includes/form_handlers/form_variables.php';
require 'includes/form_handlers/login_handler.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <!-- Incluimos el archivo en donde diseñaremos nuestro css -->
    <link rel="stylesheet" href="assets/css/start_style.css">
    <link rel="stylesheet" href="assets/css/Site.css">
    
        <style>
            html, body {
                height: 100%;
                background: linear-gradient(to bottom, #69c5b5, #336699, #154d24);
            }
            .register_error_message {
            color: #FF2211;
            font-weight: bold;
            font-family: sans-serif;
            }
            a {
                color: white;
                font-size: 30px;
                text-decoration: none;
                padding: 20px 40px;
                border-radius: 5px;
                background-color: #2673ca;
                transition: background-color 0.3s ease;
            }
    
                a:hover {
                    background-color: #093b74;
                }
        </style>
        
</head>

<body>
<centerMargin>
    <boldDarkTitle>Iniciar sesión en Blockimino</boldDarkTitle>
    <!-- //RF7 Habra una pantalla especifica para iniciar sesion -->
    <!-- //RF4 El ingreso a la plataforma sera con usuario y contraseña -->
    <!-- Crearemos un formulario y lo mandaremos a esta misma pagina -->

    <form action="login.php" method="POST">
        <input type="text" name="log_username" placeholder="Nombre de usuario"
        value="<?php
            if(isset($_POST['log_username']))
            {
                echo ($_POST['log_username']);
            }
        ?>"
        required>
        <br>
        <!-- // RF32 Se mostrara un mensaje de error si no concuerdan las credenciales del login -->
        <?php
            if(in_array("El nombre de usuario no existe!<br>", $error_array))
            {
                echo "El nombre de usuario no existe!<br>";
            }
        ?>
        <?php 
            if($correct_username)
            {
                ?>
                <input type="password" name="log_password" placeholder="Contraseña" required>
                <br>
                <?php
                    if(in_array("La contraseña es incorrecta!<br>", $error_array))
                    {
                        echo "La contraseña es incorrecta!<br>";
                    }
                    if(in_array("Usted cuenta con 1 intento mas para iniciar sesión, de lo contrario, su cuenta será bloqueada por 10 minutos<br>", $error_array))
                    {
                        echo "Usted cuenta con 1 intento mas para iniciar sesión, de lo contrario, su cuenta será bloqueada por 10 minutos<br>";
                    }
                    if(in_array("Su cuenta ha sido bloqueada temporalmente por cinco minutos<br>", $error_array))
                    {
                        echo "Su cuenta ha sido bloqueada temporalmente por cinco minutos<br>";
                    }
                    if(in_array("Usted cuenta con 1 intento mas para iniciar sesión, de lo contrario, su cuenta será bloqueada por 1 hora<br>", $error_array))
                    {
                        echo "Usted cuenta con 1 intento mas para iniciar sesión, de lo contrario, su cuenta será bloqueada por 1 hora<br>";
                    }
                    if(in_array("Su cuenta ha sido bloqueada temporalmente por una hora<br>", $error_array))
                    {
                        echo "Su cuenta ha sido bloqueada temporalmente por una hora<br>";
                    }
                    if(in_array("Usted cuenta con 1 intento mas para iniciar sesión, de lo contrario, su cuenta será bloqueada permantentemente o hasta que un administrador la desbloquee<br>", $error_array))
                    {
                        echo "Usted cuenta con 1 intento mas para iniciar sesión, de lo contrario, su cuenta será bloqueada permantentemente o hasta que un administrador la desbloquee<br>";
                    }
                    if(in_array("Su cuenta ha sido bloqueada permanentemente, notifique a un administrador para que la desbloquee<br>", $error_array))
                    {
                        echo "Su cuenta ha sido bloqueada permanentemente, notifique a un administrador para que la desbloquee<br>";
                    }
                    
                ?>
                    <centerMargin>
                        <input type="submit" name="login_button" value="Iniciar sesión">
                    </centerMargin>

                <?php
            }
            else
            {
                ?>
                    <centerMargin>
                        <input type="submit" name="login_button" value="Siguiente">
                    </centerMargin>
                <?php
            }

        ?>

    </form>
    <boldWhiteSlim>
    ¿No tienes tienes una cuenta?
    </boldWhiteSlim>
    <a href="register.php">Registrate aquí</a>
    </centerMargin>

</body>
</html>