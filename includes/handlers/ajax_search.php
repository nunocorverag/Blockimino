<?php
include("../../config/config.php");
include("../../includes/classes/Usuario.php");

$query = $_POST['query'];
$id_usuario_loggeado = $_POST['id_usuario_loggeado'];

// !NOTA HAY QUE TENER CUIDADO CON EL REDIRECCIONAMIENTO ABSOLUTO EN EL HOST
$src_pagina = 'http://localhost/blockimino/';


// + Checcar si la query tiene #
if (substr($query, 0, 1) === '#') {
    $query_info_hashtag = mysqli_query($con, "SELECT hashtag FROM hashtags WHERE hashtag LIKE '$query%' LIMIT 8");
    if(mysqli_num_rows($query_info_hashtag) > 0)
    {
        while ($fila_info_hashtag = mysqli_fetch_array($query_info_hashtag)) 
        {
            $nombre_sin_hashtag = str_replace("#", "", $fila_info_hashtag['hashtag']);

            echo "<div class='displayResultado'>
                    <a href='" . $src_pagina . "publication_hashtag.php?hashtag=" . $nombre_sin_hashtag . "' style='color: #1485BD'>
                        <div class='simbolo_hashtag'>
                            <i class='fa-solid fa-hashtag'></i>
                        </div>                   
                        <div class='liveSearchTexto'>
                            " . $nombre_sin_hashtag . "
                            <p style='margin: 0'>Hashtag</p>
                        </div>
                    </a>
                </div>";
        }
    }
} 
else 
{
    // + Separamos los elementos de la busqueda
    $busqueda = explode(" ", $query);

    // + Contara el numero de busqueda en el arreglo
    if(count($busqueda) >= 4)
    {
        $usuariosRetornadosQuery = "";
    }

    // + Esta busqueda incluye el nombre y los apellidos
    else if(count($busqueda) == 3)
    {
        $usuariosRetornadosQuery = mysqli_query($con, "SELECT * FROM usuarios WHERE (nombre LIKE '%$busqueda[0]%' AND apeP LIKE '%$busqueda[1]%' AND apeM LIKE '%$busqueda[2]%')
                                                                                AND usuario_cerrado='no' LIMIT 8");
    }

    // + Esta busqueda incluye el nombre y un apellido
    else if(count($busqueda) == 2)
    {
        $usuariosRetornadosQuery = mysqli_query($con, "SELECT * FROM usuarios WHERE 
                                                                                (nombre LIKE '%$busqueda[0]%' AND apeP LIKE '%$busqueda[1]%') 
                                                                                OR 
                                                                                (nombre LIKE '%$busqueda[0]%' AND apeM LIKE '%$busqueda[1]%')
                                                                                AND usuario_cerrado='no' LIMIT 8");
    }
    // + Esta busqueda es para los usuarios
    else if(count($busqueda) == 1)
    {
        $usuariosRetornadosQuery = mysqli_query($con, "SELECT * FROM usuarios WHERE 
                                                                                (nombre LIKE '%$busqueda[0]%') 
                                                                                OR 
                                                                                (apeP LIKE '%$busqueda[0]%')
                                                                                OR
                                                                                (apeM LIKE '%$busqueda[0]%')
                                                                                OR
                                                                                (username LIKE '$busqueda[0]%')
                                                                                AND usuario_cerrado='no' LIMIT 9");

        $gruposRetornadosQuery = mysqli_query($con, "SELECT * FROM grupos WHERE 
                                                                            (nombre_grupo LIKE '%$busqueda[0]%') 
                                                                            AND grupo_eliminado='no' LIMIT 9");
    }

    if ($query != "" && $usuariosRetornadosQuery != "")
    {
        $resultados_mostrados = 0;
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
            $resultados_mostrados ++;
            }
        }
        if(count($busqueda) == 1)
        {
            while ($fila = mysqli_fetch_array($gruposRetornadosQuery)) 
            {
                if($resultados_mostrados == 8)
                {
                    break;
                }
                else
                {
                    echo "<div class='displayResultado'>
                            <a href='" . $src_pagina . "groups/" . $fila['nombre_grupo'] . "'>
                                <div class='liveSearchFotoGrupo'>
                                    <img src='" . $src_pagina . $fila['imagen_grupo'] . "'>
                                </div>
                                <div class='liveSearchTexto'>
                                " . $fila['nombre_grupo'] . "
                                <p style='margin: 0'>Grupo</p>
                                </div>
                            </a>
                        </div>";
        
                $resultados_mostrados++;
                }
            }
        }
    }
}
?>