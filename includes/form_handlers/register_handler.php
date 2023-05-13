<!-- Este archivo procesa la informacion recibida de un POST en register.php -->
<?php

#region
//GUARDAR LA INFORMACION RECIBIDA DEL FORMULARIO Y APLICAR CAMBIOS DE FORMATO

// - register_button sera el boton de enviar nuestro formulario de registro
// $ isset -> Determina si las variables estan declaradas y son diferentes de NULL
// + Determinara si el formulario que enviamos tiene todos los campos llenos
if(isset($_POST['register_button']))
{
    // PRIMER NOMBRE
    // - $nombre -> Va a guardar el valor del nombre que enviamos del formulario
    // $ strip_tags -> Removera las etiquetas HTML y PHP de la cadena
    $nombre = strip_tags($_POST['reg_nombre']);
    // $ str_replace -> Reemplazara un string, con otro string
    // + Aqui reemplazamos los espacios ' ' con nada '' para acomodar la cadena
    $nombre = str_replace(' ', '', $nombre);
    // $ strtolower -> Pone en minusculas toda la cadena
    // $ ucfirst -> Hace que la primera letra de una cadena sea mayuscula
    // + Aqui estamos agarrando toda la cadena: La convertimos toda en minuscula y
    // + posteriormente asignamos la primera letra de la cadena mayuscula
    $nombre = ucfirst(strtolower($nombre));

    // APELLIDO PATERNO
    $apeP = strip_tags($_POST['reg_apeP']);
    $apeP = str_replace(' ', '', $apeP);
    $apeP = ucfirst(strtolower($apeP));

    // APELLIDO MATERNO
    $apeM = strip_tags($_POST['reg_apeM']);
    $apeM = str_replace(' ', '', $apeM);
    $apeM = ucfirst(strtolower($apeM));

    // EMAIL
    $email = strip_tags($_POST['reg_email']);
    $email = str_replace(' ', '', $email);
    // + En email no necesitamos que la primera letra sea mayuscula
    $email = strtolower($email);

    // USERNAME
    $username = strip_tags($_POST['reg_username']);
    $username = str_replace(' ', '', $username);
    
    // CONTRASEÑA
    $password = strip_tags($_POST['reg_password']);

    // VERIFICACION DE CONTRASEÑA
    $confPassword = strip_tags($_POST['reg_confPassword']);

    // FECHA DE CREACION DE USUARIO
    // $ date(Y-m-d) -> Regresa la fecha en el formato especificado: En este caso, año-mes-dia (aaaa-mm-dd)
    $fecha = date("Y-m-d");


#endregion

#region
//VALIDAR LA INFORMACION RECIBIDA EN EL FORMULARIO
//NOMBRE
    // $ preg_match -> Regresa verdadero si coincide uno de los caracteres ingresados
    // + Checar que el nombre no contenga ningun simbolo
    if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $nombre))
        // $ array_push -> Envia una cadena al final de un arreglo
        array_push($error_array, "Error: El nombre no puede contener simbolos!<br>");

    // + Comprobar que el nombre sea menor a 50 caracteres
    // $ strlen() -> Obtiene la cantidad de caracteres en un string
    if (strlen($nombre) > 50 || strlen($nombre) < 2)
        array_push($error_array, "Error: El nombre debe de contener entre 2 y 50 caracteres!<br>");
    
    // + Checar que el nombre no contenga numeros
    if (preg_match('/[0-9]/', $nombre))
        array_push($error_array, "Error: El nombre no puede contener numeros!<br>");

    //APELLIDO PATERNO
    // + No simbolos
    if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $apeP))
        array_push($error_array, "Error: El apellido paterno no puede contener simbolos!<br>");

    // + Rango de caracteres
    if (strlen($apeP) > 50 || strlen($apeP) < 2)
        array_push($error_array, "Error: El apellido paterno debe de contener entre 2 y 50 caracteres!<br>");
    
    // + No numeros
    if (preg_match('/[0-9]/', $apeP))
        array_push($error_array, "Error: El apellido paterno no puede contener numeros!<br>");

    //APELLIDO MATERNO
    // + No simbolos
    if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $apeM))
        array_push($error_array, "Error: El apellido materno no puede contener simbolos!<br>");

    // + Rango de caracteres
    if (strlen($apeM) > 50 || strlen($apeM) < 2)
        array_push($error_array, "Error: El apellido materno debe de contener entre 2 y 50 caracteres!<br>");
        
    // + No numeros
    if (preg_match('/[0-9]/', $apeM))
        array_push($error_array, "Error: El apellido materno no puede contener numeros!<br>");

    //EMAIL
    // + Rango de caracteres
    if (strlen($email) > 100)
        array_push($error_array, "Error: El email no puede contener mas de 100 caracteres!<br>");

    // + Checar que el email se encuentre en formato valido
    if (filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        // + Checar que el email no exista 
        $email_check = mysqli_query($con, "SELECT email FROM usuarios WHERE email='$email'");
        // $ -> mysqli_mun_rows -> Obtiene el numero de filas que se encuentran al ejecutar una query
        $num_rows = mysqli_num_rows($email_check);
        // + En este caso, si no se encontraron filas de la query, entonces significa que el email se registra por primera vez
        if($num_rows > 0)
            array_push($error_array, "Error: El email ya esta en uso!<br>");
    }
    else
        array_push($error_array, "Error: El formato del email es incorrecto!<br>");

    // RNF1 La base de datos puede registrar un nuevo usuario
    //NOMBRE DE USUARIO
    // + No simbolos
    if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $username))
        array_push($error_array, "Error: El nombre de usuario no puede contener simbolos!<br>");

    // RNF9 El nombre de usuario debera tener un minimo de 8 caracteres y un maximo de 12
    // + Rango de caracteres
    if (strlen($username) > 12 || strlen($username) < 8)
        array_push($error_array, "Error: El nombre de usuario debe de contener entre 8 y 12 caracteres!<br>");

    // + No numeros
    if (preg_match('/[0-9]/', $username))
        array_push($error_array, "Error: El nombre de usuario no puede contener numeros!<br>");

    $check_username_query = mysqli_query($con, "SELECT username FROM usuarios WHERE username='$username'");

    // + Checar que el nombre de usuario no exista
    if(mysqli_num_rows($check_username_query) != 0)
        array_push($error_array, "Error: El nombre de usuario ya existe!<br>");

    // RNF2 La base de datos puede registrar una contraseña a un nuevo usuario
    //CONTRASEÑA
    // + Rango de caracteres
    if (strlen($password) < 8)
        array_push($error_array_password, "Error: La contraseña debe de contener mas de 8 caracteres!<br>");

    // RNF6 La contraseña debera contener al menos un número
    // + Contenga numeros
    if (!(preg_match('/[0-9]/', $password)))
        array_push($error_array, "Error: La contraseña debe contener al menos un numero!<br>");

    // + Contenga simbolos
    if (!(preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $password)))
        array_push($error_array, "Error: La contraseña debe contener al menos un simbolo!<br>");

    // RNF5 La contraseña debera contener al menos una letra mayúscula
    // + Contenga mayusculas
    if(!(preg_match('/[A-Z]/', $password)))
        array_push($error_array, "Error: La contraseña debe contener al menos una mayuscula!<br>");

    // RNF4 La contraseña debera contener al menos una letra minúscula
    // + Contenga minusculas
    if(!(preg_match('/[a-z]/', $password)))
        array_push($error_array, "Error: La contraseña debe contener al menos una minuscula!<br>");

    // + Checar que coincidan las contraseñas
    if($password != $confPassword)
        array_push($error_array, "Error: Las contraseñas no coinciden!<br>");
    
    if(empty($error_array))
    {
        // + Encriptamos la contraseña antes de meterla a la base de datos
        $password = md5($password);

        // + Asignamos la foto de perfil a una default
        $profile_pic = "assets/images/profile_pics/default/default.png";

        // RNF7 El nombre de usuario y la contraseña se guardaran en la base de datos
        // + Esta query insertara todos los valores a la tabla
        // RNF10 El tipo de usuario sera asignado desde la base de datos
        // RNF11 El tipo de usuario por defecto es usuario normal
        // RF17 Por defecto el tipo de usuario es normal
        // RF21 El usuario normal sera el tipo de usuario mas comun, este estara disponible para todos los que se registren en nuestra página
        $tipo_usuario = "normal";
        $query = mysqli_query($con, "INSERT INTO usuarios VALUES ('', '$nombre', '$apeP', '$apeM', '$email', '$username', '$password', '0', '0', '$profile_pic', '$fecha', 'no', '$tipo_usuario', ',', ',', ',', ',','0')");

        // + redirigimos al usuario a la pantalla de iniciar sesión
        header("Location: login.php");

    }
#endregion
}
?>