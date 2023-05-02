<!-- Este archivo comprobara que la informacion enviada por el usuario al intentar iniciar sesion sea correcta -->

<?php
if(isset($_POST['login_button']))
{
    // + Guardamos en la variable username el nombre de usuario proporcionado por el usuario
    $username = ($_POST['log_username']);

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
        $id_usuario = $database_user['id_usuario'];
        $query_verificar_si_hay_sanciones = mysqli_query($con, "SELECT * FROM sanciones WHERE id_usuario_sancionado='$id_usuario'");
        if(mysqli_num_rows($query_verificar_si_hay_sanciones) > 0)
        {
            $query_seleccionar_ultima_sancion_usuario = mysqli_query($con, "SELECT * FROM sanciones WHERE id_usuario_sancionado='$id_usuario' AND id_sancion = (SELECT MAX(id_sancion) FROM sanciones WHERE id_usuario_sancionado ='$id_usuario')");
            $fila_info_sancion = mysqli_fetch_array($query_seleccionar_ultima_sancion_usuario);
            $tiempo_sancion = $fila_info_sancion['fecha_sancion'];
            $tipo_sancion = $fila_info_sancion['tipo_sancion'];

            $tiempo_actual = date("Y-m-d H:i:s");

            $tiempo_actual = strtotime($tiempo_actual);
            $tiempo_sancion = strtotime($tiempo_sancion);

            $tiempo_restante = $tiempo_sancion - $tiempo_actual;

            if($tiempo_restante <= 0 && $tipo_sancion == "temporal")
            {
                $query_eliminar_sanciones_temporales = mysqli_query($con, "DELETE FROM sanciones WHERE id_usuario_sancionado='$id_usuario' AND tipo_sancion='temporal'");
            }
        }

        $query_verificar_que_usuario_no_este_sancionado = mysqli_query($con, "SELECT * FROM sanciones WHERE id_usuario_sancionado='$id_usuario'");


        if(mysqli_num_rows($query_verificar_que_usuario_no_este_sancionado) == 0)
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
                // + Guardamos el id del usuario en una variable cookie
                setcookie("id_usuario", $id_usuario, time() + 3600, "/");

                //+ Redirigimos al usuario a la pagina principal de su perfil
                // $ -> Manda al usuario a un encabezado http
                $query_resetear_intentos = mysqli_query($con, "UPDATE usuarios SET intentos_inicio_sesion=0 WHERE username='$username'");
                header("Location: home.php");
            }
            // + Si no tenemos una fila en resultado, significa que la contraseña es incorrecta
            else
            {
                $id_usuario = $database_user['id_usuario'];
                // + Incrementar los intentos de inicio de sesion
                $query_incrementar_intentos = mysqli_query($con, "UPDATE usuarios SET intentos_inicio_sesion = intentos_inicio_sesion + 1 WHERE id_usuario='$id_usuario'");
                $query_seleccionar_intentos = mysqli_query($con, "SELECT intentos_inicio_sesion FROM usuarios WHERE id_usuario='$id_usuario'");
                $fila_intentos = mysqli_fetch_array($query_seleccionar_intentos);
                $numero_intentos = $fila_intentos['intentos_inicio_sesion'];
                if($numero_intentos == 4)
                {
                    $info = "Usted cuenta con 1 intento mas para iniciar sesión, de lo contrario, su cuenta será bloqueada por 10 minutos<br>";
                    array_push($error_array, $info);
                }
                else if($numero_intentos == 5)
                {
                    // + Aplicar sancion temp 10 min, avisar
                    $razon = "Demasiados intentos fallidos de inicio de sesión (5)";
                    $tipo_sancion = "temporal";

                    $fecha_actual = date('Y-m-d H:i:s');
                    $query_aplicar_sancion = mysqli_query($con, "INSERT INTO sanciones VALUES ('', '$razon', '$tipo_sancion', '$fecha_sancion', '$id_usuario', NULL, NULL, NULL)");
                    $info = "Su cuenta ha sido bloqueada temporalmente, si vuelve a fallar, será bloqueada por 1 hora<br>";
                    array_push($error_array, $info);

                }
                else if($numero_intentos == 6)
                {
                    // + Aplicar sancion temp 1 hora, avisar de sancion inminente
                    $razon = "Intentos fallidos de inicio de sesión presistentes (6)";
                    $tipo_sancion = "temporal";

                    $fecha_actual = date('Y-m-d H:i:s');
                    $fecha_sancion = date('Y-m-d H:i:s', strtotime($fecha_actual));
                    $query_aplicar_sancion = mysqli_query($con, "INSERT INTO sanciones VALUES ('', '$razon', '$tipo_sancion', '$fecha_sancion', '$id_usuario', NULL, NULL, NULL)");
                    $info = "Su cuenta ha sido bloqueada temporalmente, si vuelve a fallar, será bloqueada permanentemente hasta que un administrador la desbloquee<br>";
                    array_push($error_array, $info);
                }
                else if($numero_intentos == 7)
                {
                    // + Eliminar sanciones temporales
                    $query_eliminar_sanciones_temporales = mysqli_query($con, "DELETE FROM sanciones WHERE id_usuario_sancionado='$id_usuario' AND tipo_sancion='temporal'");

                    // + Aplicar sancion permanente
                    $razon = "Exceso de intentos fallidos de sesión";
                    $tipo_sancion = "permanente";
                    $query_aplicar_sancion = mysqli_query($con, "INSERT INTO sanciones VALUES ('', '$razon', '$tipo_sancion', NULL, '$id_usuario', NULL, NULL, NULL)");
                    $info = "Su cuenta ha sido bloqueada permanentemente, notifique a un administrador para que la desbloquee<br>";
                    array_push($error_array, $info);
                }

                array_push($error_array, "La contraseña es incorrecta!<br>");
            }
        }
        else
        {
            header("Location: " . dirname($_SERVER['PHP_SELF']) . "/sanctioned.php?username=" . $username);
        }
    }
}
?>
