<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Incluimos el archivo en donde diseñaremos nuestro css -->
    <link rel="stylesheet" href="assets/css/start_style.css">
    <link rel="stylesheet" href="assets/css/Site.css">
    
    <style>
        html, body {
            height: 100%;
            background: linear-gradient(to bottom, #08090a, #191c1f, #2e2e2e);
        }

        .registerButton {
            color: white;
            font-size: 30px;
            text-decoration: none;
            padding: 20px 40px;
            border-radius: 5px;
            background-color: brown;
            transition: background-color 0.3s ease;
        }

            .registerButton:hover {
                background-color: #63180e;
            }


        .loginButton {
            color: white;
            font-size: 30px;
            text-decoration: none;
            padding: 20px 40px;
            border-radius: 5px;
            background-color: #3d76f3;
            transition: background-color 0.3s ease;
        }

            .loginButton:hover {
                background-color: #1a3b83;
            }
    </style>
    <title>Bienvenido!</title>
</head>

<body>
    <div class="cuerpo_inicio">
    <centerMargin>
        <boldClearTitle>Bienvenido a Blockimino</boldClearTitle>
            <a class="registerButton" href="register.php">
                Registrarse
            </a>
            <br>
            <a class="loginButton" href="login.php">
                Iniciar Sesión
            </a>
        </centerMargin>
    </div>

    <footer>
        <img src="assets/images/icons/back.png">
    </footer>
</body>

</html>