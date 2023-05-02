<?php
if(isset($_POST['actualizar_informacion']))
{
    $error_array_info = array();
    $successful_array_info = array();
    $nombre = $_POST['nombre'];
    $apeP = $_POST['apeP'];
    $apeM = $_POST['apeM'];
    $email = $_POST['email'];
    $username = $_POST['username'];


//VALIDAR LA INFORMACION RECIBIDA EN EL FORMULARIO
//NOMBRE
    // $ preg_match -> Regresa verdadero si coincide uno de los caracteres ingresados
    // + Checar que el nombre no contenga ningun simbolo
    if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $nombre))
        // $ array_push -> Envia una cadena al final de un arreglo
        array_push($error_array_info, "Error: El nombre no puede contener simbolos!<br>");

    // + Comprobar que el nombre sea menor a 50 caracteres
    // $ strlen() -> Obtiene la cantidad de caracteres en un string
    if (strlen($nombre) > 50 || strlen($nombre) < 2)
        array_push($error_array_info, "Error: El nombre debe de contener entre 2 y 50 caracteres!<br>");
    
    // + Checar que el nombre no contenga numeros
    if (preg_match('/[0-9]/', $nombre))
        array_push($error_array_info, "Error: El nombre no puede contener numeros!<br>");

    //APELLIDO PATERNO
    // + No simbolos
    if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $apeP))
        array_push($error_array_info, "Error: El apellido paterno no puede contener simbolos!<br>");

    // + Rango de caracteres
    if (strlen($apeP) > 50 || strlen($apeP) < 2)
        array_push($error_array_info, "Error: El apellido paterno debe de contener entre 2 y 50 caracteres!<br>");
    
    // + No numeros
    if (preg_match('/[0-9]/', $apeP))
        array_push($error_array_info, "Error: El apellido paterno no puede contener numeros!<br>");

    //APELLIDO MATERNO
    // + No simbolos
    if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $apeM))
        array_push($error_array_info, "Error: El apellido materno no puede contener simbolos!<br>");

    // + Rango de caracteres
    if (strlen($apeM) > 50 || strlen($apeM) < 2)
        array_push($error_array_info, "Error: El apellido materno debe de contener entre 2 y 50 caracteres!<br>");
        
    // + No numeros
    if (preg_match('/[0-9]/', $apeM))
        array_push($error_array_info, "Error: El apellido materno no puede contener numeros!<br>");

    //EMAIL
    // + Rango de caracteres
    if (strlen($email) > 100)
        array_push($error_array_info, "Error: El email no puede contener mas de 100 caracteres!<br>");

    // + Checar que el email se encuentre en formato valido
    if (filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        // + Checar que el email no exista 

        $email_check = mysqli_query($con, "SELECT id_usuario FROM usuarios WHERE email='$email'");
        $num_rows_query_email = mysqli_num_rows($email_check);
        $id_usuario_encontrado = "";
        if($num_rows_query_email > 0)
        {
            $fila = mysqli_fetch_array($email_check);
            $id_usuario_encontrado = $fila['id_usuario'];
        }
        // + En este caso, si no se encontraron filas de la query, entonces significa que el email se registra por primera vez
        if(!($id_usuario_encontrado == "" || $id_usuario_encontrado == $id_usuario_loggeado))
            array_push($error_array_info, "Error: El email ya esta en uso!<br>");
    }
    else
        array_push($error_array_info, "Error: El formato del email es incorrecto!<br>");

    //NOMBRE DE USUARIO
    // + No simbolos
    if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $username))
        array_push($error_array_info, "Error: El nombre de usuario no puede contener simbolos!<br>");

    // + Rango de caracteres
    if (strlen($username) > 12 || strlen($username) < 8)
        array_push($error_array_info, "Error: El nombre de usuario debe de contener entre 8 y 12 caracteres!<br>");

    // + No numeros
    if (preg_match('/[0-9]/', $username))
        array_push($error_array_info, "Error: El nombre de usuario no puede contener numeros!<br>");

    $check_username_query = mysqli_query($con, "SELECT username, id_usuario FROM usuarios WHERE username='$username'");

    // + Checar que el nombre de usuario no exista
    $id_usuario_encontrado = "";
    $num_rows_query_username = mysqli_num_rows($check_username_query);
    if($num_rows_query_username > 0)
    {
        $fila = mysqli_fetch_array($check_username_query);
        $id_usuario_encontrado = $fila['id_usuario'];
    }
    // + En este caso, si no se encontraron filas de la query, entonces significa que el email se registra por primera vez
    if(!($id_usuario_encontrado == "" || $id_usuario_encontrado == $id_usuario_loggeado))
        array_push($error_array_info, "Error: El nombre de usuario ya existe!<br>");

    if(empty($error_array_info))
    {
        $query_actualizar_informacion = mysqli_query($con, "UPDATE usuarios SET nombre='$nombre', apeP='$apeP', apeM='$apeM', email='$email', username='$username' WHERE id_usuario='$id_usuario_loggeado'");
        // + Mostramos un mensaje de que la informacion se actualizo correctamente
        array_push($successful_array_info, "<span style='color: #14c800;'>Información actualizada!<br><br>");
    }

}
else
{
    $error_array_info = array();
    $successful_array_info = array();
}

if(isset($_POST['actualizar_contra']))
{
    $error_array_password = array();
    $successful_array_password = array();
    $old_password = strip_tags($_POST['old_password']);
    $new_password = strip_tags($_POST['new_password']);
    $confirm_new_password = strip_tags($_POST['confirm_new_password']);

    $query_password = mysqli_query($con, "SELECT password FROM usuarios WHERE id_usuario='$id_usuario_loggeado'");
    $fila = mysqli_fetch_array($query_password);
    $db_password = $fila['password'];
    if(md5($old_password) == $db_password)
    {
        //CONTRASEÑA
        // + Rango de caracteres
        if (strlen($new_password) < 8)
            array_push($error_array_password, "Error: La contraseña debe de contener mas de 8 caracteres!<br>");

        // + Contenga numeros
        if (!(preg_match('/[0-9]/', $new_password)))
            array_push($error_array_password, "Error: La contraseña debe contener al menos un numero!<br>");

        // + Contenga simbolos
        if (!(preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $new_password)))
            array_push($error_array_password, "Error: La contraseña debe contener al menos un simbolo!<br>");

        // + Contenga mayusculas
        if(!(preg_match('/[A-Z]/', $new_password)))
            array_push($error_array_password, "Error: La contraseña debe contener al menos una mayuscula!<br>");

        // + Contenga minusculas
        if(!(preg_match('/[a-z]/', $new_password)))
            array_push($error_array_password, "Error: La contraseña debe contener al menos una minuscula!<br>");

        // + Checar que coincidan las contraseñas
        if($new_password != $confirm_new_password)
            array_push($error_array_password, "Error: Las contraseñas no coinciden!<br>");

        if(empty($error_array_password))
        {
            $new_password_md5 = md5($new_password);
            $query_actualizar_contra = mysqli_query($con, "UPDATE usuarios SET password='$new_password_md5' WHERE id_usuario='$id_usuario_loggeado'");
            // + Mostramos un mensaje de que la contraseña se actualizo correctamente
            array_push($successful_array_password, "<span style='color: #14c800;'>La contraseña ha sido actualizada!<br><br>");
        }
    }
    else
    {
        array_push($error_array_password, "Error: La contraseña es incorrecta, se requiere la contraseña antigua para cambiarla!<br>");
    }
        
}
else
{
    $error_array_password = array();
    $successful_array_password = array();
}

if(isset($_POST['cerrar_cuenta']))
{
    header("Location: closed_account.php");
}

?>