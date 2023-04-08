<?php
include("../../config/config.php");
include("../../includes/classes/Usuario.php");

$query = $_POST['query'];
$id_usuario_loggeado = $_POST['id_usuario_loggeado'];

// + Separamos los elementos de la busqueda
$nombres = explode(" ", $query);

// + Contara el numero de nombres en el arreglo
if(count($nombres) >= 4)
{
    $usuariosRetornadosQuery = "";
}

// + Esta busqueda incluye el nombre y los apellidos
else if(count($nombres) == 3)
{
    $usuariosRetornadosQuery = mysqli_query($con, "SELECT * FROM usuarios WHERE (nombre LIKE '%$nombres[0]%' AND apeP LIKE '%$nombres[1]%' AND apeM LIKE '%$nombres[2]%')
                                                                            AND usuario_cerrado='no' LIMIT 8");
}

// + Esta busqueda incluye el nombre y un apellido
else if(count($nombres) == 2)
{
    $usuariosRetornadosQuery = mysqli_query($con, "SELECT * FROM usuarios WHERE 
                                                                            (nombre LIKE '%$nombres[0]%' AND apeP LIKE '%$nombres[1]%') 
                                                                            OR 
                                                                            (nombre LIKE '%$nombres[0]%' AND apeM LIKE '%$nombres[1]%')
                                                                            AND usuario_cerrado='no' LIMIT 8");
}
// + Esta busqueda es para los usuarios
else if(count($nombres) == 1)
{
    $usuariosRetornadosQuery = mysqli_query($con, "SELECT * FROM usuarios WHERE 
                                                                            (nombre LIKE '%$nombres[0]%') 
                                                                            OR 
                                                                            (apeP LIKE '%$nombres[0]%')
                                                                            OR
                                                                            (apeM LIKE '%$nombres[0]%')
                                                                            OR
                                                                            (username LIKE '$nombres[0]%')
                                                                            AND usuario_cerrado='no' LIMIT 8");
}

if($query != "")
{
    // !NOTA HAY QUE TENER CUIDADO CON EL REDIRECCIONAMIENTO ABSOLUTO EN EL HOST
    $src_pagina = 'http://localhost/blockimino/';

    while($fila = mysqli_fetch_array($usuariosRetornadosQuery))
    {
        $objeto_usuario_loggeado = new Usuario($con, $id_usuario_loggeado);
        // + Esto para evitar que aoarezca el usuario loggaedo
        if($fila['id_usuario'] != $id_usuario_loggeado)
        {
            $amigos_mutuos = $objeto_usuario_loggeado->obtenerAmigosMutuos($fila['id_usuario']) . " amigos en comun";
        }
        else
        {
            $amigos_mutuos = "";
        }
        if ($fila['id_usuario'] != $id_usuario_loggeado)
        {
            echo "<div class='displayResultado'>
                    <a href='" . $src_pagina . $fila['username'] . "' style='color: #1485BD'>
                        <div class='liveSearchFotoPerfil'>
                            <img src='" . $src_pagina . $fila['foto_perfil'] . "'>
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
?>