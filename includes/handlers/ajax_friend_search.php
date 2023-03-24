<?php
include ("../../config/config.php");
include ("../classes/Usuario.php");

$busqueda = $_POST['busqueda'];
$id_usuario_loggeado = $_POST['id_usuario_loggeado'];

// + Separara el string en caso de que el usuario ingrese un nombre completo
$nombres = explode(" ", $busqueda);

// + Vamos a hacer una serie de busquedas para predecir lo que buscaran

// + Contara el numero de nombres en el arreglo
if(count($nombres) >= 4)
{
    $usuariosRetornados = "";
}

// + Esta busqueda incluye el nombre y los apellidos
else if(count($nombres) == 3)
{
    $usuariosRetornados = mysqli_query($con, "SELECT * FROM usuarios WHERE (nombre LIKE '%$nombres[0]%' AND apeP LIKE '%$nombres[1]%' AND apeM LIKE '%$nombres[2]%')
                                                                            AND usuario_cerrado='no' LIMIT 8");
}

// + ESta busqueda incluye el nombre y un apellido
else if(count($nombres) == 2)
{
    $usuariosRetornados = mysqli_query($con, "SELECT * FROM usuarios WHERE 
                                                                            (nombre LIKE '%$nombres[0]%' AND apeP LIKE '%$nombres[1]%') 
                                                                            OR 
                                                                            (nombre LIKE '%$nombres[0]%' AND apeM LIKE '%$nombres[1]%')
                                                                            AND usuario_cerrado='no' LIMIT 8");
}
// + Esta busqueda es para los usuarios
else if(count($nombres) == 1)
{
    $usuariosRetornados = mysqli_query($con, "SELECT * FROM usuarios WHERE 
                                                                            (nombre LIKE '%$nombres[0]%') 
                                                                            OR 
                                                                            (apeP LIKE '%$nombres[0]%')
                                                                            OR
                                                                            (apeM LIKE '%$nombres[0]%')
                                                                            OR
                                                                            (username LIKE '$nombres[0]%')
                                                                            AND usuario_cerrado='no' LIMIT 8");
}

if($busqueda != "")
{
    if ($usuariosRetornados != null)
    {
        while($fila = mysqli_fetch_array($usuariosRetornados))
        {
            $usuario = new Usuario($con, $id_usuario_loggeado);
            if($fila['id_usuario'] != $id_usuario_loggeado)
            {
                if($usuario->obtenerAmigosMutuos($fila['id_usuario']) == 1)
                {
                    $amigos_mutuos = $usuario->obtenerAmigosMutuos($fila['id_usuario']) . " Amigo en comun";
                }
                else
                {
                    $amigos_mutuos = $usuario->obtenerAmigosMutuos($fila['id_usuario']) . " Amigos en comun";
                }
            }
            else
            {
                $amigos_mutuos = "";
            }
    
            if($usuario->esAmigo($fila['id_usuario']) && $fila['id_usuario'] != $id_usuario_loggeado)
            {
                echo "  <div class='displayResultado'>
                            <a href='messages.php?u=" . $fila['username'] . "' style='color: 000'>
                                <div class='liveSearchFotoPerfil'>
                                    <img src='". $fila['foto_perfil'] . "'>
                                </div>
                                
                                <div class='liveSearchTexto'>
                                    " . $fila['nombre'] . " " . $fila['apeP'] . " " . $fila['apeM'] . "
                                    <p style='margin: 0'> " .$fila['username'] . "</p>
                                    <p id='gris'> ". $amigos_mutuos . "</p>
                                </div>
                                </a>
                        </div>";    
            }
        }
    }
}

?>