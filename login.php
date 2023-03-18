<!-- En este archivo un usuario ya existente podra iniciar sesion -->
<?php
//Utilizaremos el archivo config.php que tiene la conexion a nuestra base de datos
require 'config/config.php';
require 'includes/form_handlers/form_variables.php';
require 'includes/form_handlers/login_handler.php';
?>
<!DOCTYPE html>
<html lang="en">

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
    <h1>Registrarse en Blockimino</h1>
    <!-- //RF7 Habra una pantalla especifica para iniciar sesion -->
    <!-- //RF4 El ingreso a la plataforma sera con usuario y contraseña -->
    <!-- Crearemos un formulario y lo mandaremos a esta misma pagina -->
    <form action="login.php" method="POST">
        <input type="text" name="log_username" placeholder="Nombre de usuario"
        value="<?php
            if(isset($_SESSION['log_username']))
            {
                echo ($_SESSION['log_username']);
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
        ?>
        <input type="submit" name="login_button" value="Iniciar sesión">
    </form>
</body>

</html>