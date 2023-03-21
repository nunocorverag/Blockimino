<!-- Este archivo comprobara que la informacion enviada por el usuario al intentar iniciar sesion sea correcta -->

<?php
if(isset($_POST['login_button']))
{
    // + Guardamos en la variable username el nombre de usuario proporcionado por el usuario
    $username = ($_POST['log_username']);
    // + Guardamos el nombre de usuario en una variable de sesion
    $_SESSION['log_username'] = $username;

    // + Guardamos en una variable password la contraseña encriptada proporcionada por el usuario
    $password = md5($_POST['log_password']);

    // + Guardamos en una variable la consulta del usuario ingresado por el usuario a la base de datos
    $check_username_query = mysqli_query($con, "SELECT * FROM usuarios WHERE username='$username'");
    // + Guardamos la consulta convertida en filas en una variable
    $database_user = mysqli_fetch_array($check_username_query);
    if(mysqli_num_rows($check_username_query) > 0)
    {
        $database_username = $database_user['username'];
    }
    else
    {
        $database_username = "";
    }

    // + Si no tenemos una fila en resultado, significa que el usuario es incorrecto
    if ($database_username != $username)
    {
        array_push($error_array, "El nombre de usuario no existe!<br>");
    }

    // + Si tenemos una fila en resultado, significa que el usuario es correcto
    if ($database_username == $username)
    {
        // + Guardamos en una variable la consulta de la contraseña ingresada por el usuario a la base de datos
        $check_password_query = mysqli_query($con, "SELECT * FROM usuarios WHERE username='$username' && password='$password'");
        // + Guardamos la consulta convertida en filas en una variable
        $check_password = mysqli_num_rows($check_password_query);
    
        // + Si tenemos una fila en resultado, significa que la contraseña es correcta
        if ($check_password == 1)
        {
            // + En este punto, la contraseña y nombre de usuario son correctos, por lo que podemos iniciar sesión
            // + We store the results of the query in a row
            // $ mysli_fetch_array -> Nos permite acceder a los campos de informacion guardada en una query
            $row = mysqli_fetch_array($check_password_query);
            // - Esta variable guarda el id del usuario
            $id_usuario = $row['id_usuario'];
            // - Guardamos el nombre de usuario directamente de la base de datos en una variable
            $username = $row['username'];
            // - Guardamos el tipo de usuario en una variable para comprobar que tipo de usuario es
            $tipo_usuario = $row['tipo'];

            // + Realizamos una query para verificar si la cuenta se encuentra cerrada
            $user_closed_query = mysqli_query($con, "SELECT * FROM usuarios WHERE username='$username' AND usuario_cerrado='si'");
            // + Si tenemos informacion, significa que si
            if(mysqli_num_rows($user_closed_query) == 1)
            {
                // + Cambiamos "usuario_cerrado" a no, ya que el usuario ha iniciado sesion
                $reopen_account = mysqli_query($con, "UPDATE usuarios SET usuario_cerrado='no' WHERE username='$username'");
            }
            $_SESSION['id_usuario'] = $id_usuario;
            // + Guardamos el nombre de usuario en una variable de sesion
            $_SESSION['username'] = $username;
            $_SESSION['tipo'] = $tipo_usuario;
            //+ Redirigimos al usuario a la pagina principal de su perfil
            // $ -> Manda al usuario a un encabezado http
            //RF10 Se podra ingresar con usuario normal
            if($tipo_usuario == "normal")
            {
                header("Location: home.php");
            }
            //RF11 Se podra ingresar con usuario moderador
            else if($tipo_usuario == "moderador")
            {
                header("Location: home.php");
            }
            //RF12 Se podra ingresar con usuario administrador
            else if($tipo_usuario == "administrador")
            {
                header("Location: home.php");
            }
        }
        // + Si no tenemos una fila en resultado, significa que la contraseña es incorrecta
        else
        {
            array_push($error_array, "La contraseña es incorrecta!<br>");
        }
    }
}
?>
