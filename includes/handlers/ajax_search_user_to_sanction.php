<?php
include("../../config/config.php");
include("../../includes/classes/Usuario.php");

$query = $_POST['query'];
$id_usuario_loggeado = $_POST['id_usuario_loggeado'];
$query_tipo_usuario = mysqli_query($con, "SELECT tipo FROM usuarios WHERE id_usuario='$id_usuario_loggeado'");
$fila_tipo_usuario = mysqli_fetch_array($query_tipo_usuario);
$tipo_usuario = $fila_tipo_usuario['tipo'];

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

if($query != "" && $usuariosRetornadosQuery != "")
{
    // !NOTA HAY QUE TENER CUIDADO CON EL REDIRECCIONAMIENTO ABSOLUTO EN EL HOST
    $src_pagina = 'http://localhost/blockimino/';

    while($fila = mysqli_fetch_array($usuariosRetornadosQuery))
    {
        $id_usuario_buscado = $fila['id_usuario'];
        $query_tipo_usuario_buscado = mysqli_query($con, "SELECT tipo FROM usuarios WHERE id_usuario='$id_usuario_buscado'");
        $fila_tipo_usuario_buscado = mysqli_fetch_array($query_tipo_usuario_buscado);
        $tipo_usuario_buscado = $fila_tipo_usuario_buscado['tipo'];

        if($tipo_usuario == "administrador")
        {
            if ($fila['id_usuario'] != $id_usuario_loggeado && $tipo_usuario_buscado != "administrador")
            {
                echo "<div class='displayResultado'>
                        <div class='liveSearchFotoPerfil'>
                            <img src='" . $src_pagina . $fila['foto_perfil'] . "'>
                        </div>
                        <div class='liveSearchTexto'>
                            " . $fila['nombre'] . " " . $fila['apeP'] . " " . $fila['apeM'] . "
                            <div class='username_to_sanction'>
                                ".$fila['username']."
                            </div>
                        </div>
                    </div>";
            }
        }
        if($tipo_usuario == "moderador")
        {
            if ($fila['id_usuario'] != $id_usuario_loggeado && $tipo_usuario_buscado != "administrador" && $tipo_usuario_buscado != "moderador")
            {
                echo "<div class='displayResultado'>
                        <div class='liveSearchFotoPerfil'>
                            <img src='" . $src_pagina . $fila['foto_perfil'] . "'>
                        </div>
                        <div class='liveSearchTexto'>
                            " . $fila['nombre'] . " " . $fila['apeP'] . " " . $fila['apeM'] . "
                            <div class='username_to_sanction'>
                                ".$fila['username']."
                            </div>
                        </div>
                    </div>";
            }
        }
    }
}
?>