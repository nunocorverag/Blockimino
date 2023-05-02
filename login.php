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
    <!-- En este archivo estara el estilo css del inicio de nuestra pagina -->
    <link rel="stylesheet" href="assets/css/home_style.css">
    <!-- Incluimos el archivo en donde diseñaremos nuestro css -->
    <link rel="stylesheet" href="assets/css/start_style.css">
</head>

<body>
    <h1>Iniciar sesión en Blockimino</h1>
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
            if(in_array("Su cuenta ha sido bloqueada temporalmente, si vuelve a fallar, será bloqueada por 1 hora<br>", $error_array))
            {
                echo "Su cuenta ha sido bloqueada temporalmente, si vuelve a fallar, será bloqueada por 1 hora<br>";
            }
            if(in_array("Su cuenta ha sido bloqueada temporalmente, si vuelve a fallar, será bloqueada permanentemente hasta que un administrador la desbloquee<br>", $error_array))
            {
                echo "Su cuenta ha sido bloqueada temporalmente, si vuelve a fallar, será bloqueada permanentemente hasta que un administrador la desbloquee<br>";
            }
            if(in_array("Su cuenta ha sido bloqueada permanentemente, notifique a un administrador para que la desbloquee<br>", $error_array))
            {
                echo "Su cuenta ha sido bloqueada permanentemente, notifique a un administrador para que la desbloquee<br>";
            }
        ?>
        <input type="submit" name="login_button" value="Iniciar sesión">
    </form>
    ¿No tienes tienes una cuenta?<a href="register.php">Registrate aquí</a>
</body>

</html>