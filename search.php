<?php
include("includes/header.php");

if(isset($_GET['query']))
{
    $query = $_GET['query'];
}
else
{
    $query = "";
}

if(isset($_GET['tipo']))
{
    $tipo = $_GET['tipo'];
    if($tipo == "hashtag")
    {
        if (substr($query, 0, 1) === "#") 
        {
            // + Quitar el primer carácter (#) de la query
            $query = substr($query, 1);
            header("Location: search.php?query=" . $query ."&tipo=hashtag");
        }
    }
}
else if ($_GET['tipo'] != "hashtag")
{
    $tipo = "usuarios_nombres_y_grupos";
}

// !NOTA HAY QUE TENER CUIDADO CON EL REDIRECCIONAMIENTO ABSOLUTO EN EL HOST
$src_pagina = 'http://localhost/blockimino/';
?>

<div class="columna_principal" id="columna_principal">
    <?php
    if($query == "" && $tipo != "hashtag")
    {
        echo "Debes ingresar algo en la barra de busqueda";
    }
    else
    {
        if($tipo == "hashtag")
        {
            $hashtagsRetornadosQuery = mysqli_query($con, "SELECT hashtag FROM hashtags WHERE hashtag LIKE '#$query%'");
        }
        else if($tipo == "grupo")
        {
            $gruposRetornadosQuery = mysqli_query($con, "SELECT * FROM grupos WHERE 
                                                                            (nombre_grupo LIKE '%$query[0]%') 
                                                                            AND grupo_eliminado='no'");
        }
        // + Si el tipo de query es para un nombre de usuario
        else if($tipo == "username")
        {
            $usuariosRetornadosQuery = mysqli_query($con, "SELECT * FROM usuarios WHERE username LIKE '%$query%' AND usuario_cerrado='no'");
        }
        else if ($tipo == "nombre")
        {
            // + Separamos los elementos de la busqueda
            $nombres = explode(" ", $query);

            // + Esta busqueda incluye el nombre y los apellidos
            if(count($nombres) == 3)
            {
                $usuariosRetornadosQuery = mysqli_query($con, "SELECT * FROM usuarios WHERE (nombre LIKE '%$nombres[0]%' AND apeP LIKE '%$nombres[1]%' AND apeM LIKE '%$nombres[2]%')
                                                                                        AND usuario_cerrado='no'");
            }

            // + Esta busqueda incluye el nombre y un apellido
            else if(count($nombres) == 2)
            {
                $usuariosRetornadosQuery = mysqli_query($con, "SELECT * FROM usuarios WHERE 
                                                                                        (nombre LIKE '%$nombres[0]%' AND apeP LIKE '%$nombres[1]%') 
                                                                                        OR 
                                                                                        (nombre LIKE '%$nombres[0]%' AND apeM LIKE '%$nombres[1]%')
                                                                                        AND usuario_cerrado='no'");
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
                                                                                        AND usuario_cerrado='no'");
            }
        }
        else if($tipo == "usuarios_nombres_y_grupos")
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
                                                                                        AND usuario_cerrado='no'");
            }

            // + Esta busqueda incluye el nombre y un apellido
            else if(count($busqueda) == 2)
            {
                $usuariosRetornadosQuery = mysqli_query($con, "SELECT * FROM usuarios WHERE 
                                                                                        (nombre LIKE '%$busqueda[0]%' AND apeP LIKE '%$busqueda[1]%') 
                                                                                        OR 
                                                                                        (nombre LIKE '%$busqueda[0]%' AND apeM LIKE '%$busqueda[1]%')
                                                                                        AND usuario_cerrado='no'");
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
                                                                                        AND usuario_cerrado='no'");

                $gruposRetornadosQuery = mysqli_query($con, "SELECT * FROM grupos WHERE 
                                                                                    (nombre_grupo LIKE '%$busqueda[0]%') 
                                                                                    AND grupo_eliminado='no'");
            }
        }

        if($tipo == "hashtag")
        {
            // + Verificar si los resultados fueron encontrados
            if(mysqli_num_rows($hashtagsRetornadosQuery) == 0)
            {
                echo "No se enontro nada que incluya la siguiente busqueda: " . $query;
            }
            else if(mysqli_num_rows($hashtagsRetornadosQuery) != 1)
            {
                echo mysqli_num_rows($hashtagsRetornadosQuery) . " resultados encontrados: <br> <br>";
            }
            else
            {
                echo mysqli_num_rows($hashtagsRetornadosQuery) . " resultado encontrado: <br> <br>";
            }

            echo "<p id='gris'>Intenta buscar: </p>";
            echo "  <ul>
                        <li><a href='search.php?query=" . $query ."&tipo=usuarios_nombres_y_grupos'>Nombres de usuario, nombres y grupos</a></li>
                        <li><a href='search.php?query=" . $query ."&tipo=nombre'>Nombres</a></li>
                        <li><a href='search.php?query=" . $query ."&tipo=username'>Nombres de Usuario</a></li>
                        <li><a href='search.php?query=" . $query ."&tipo=grupo'>Grupos</a></li>
                        <li><a href='search.php?query=" . $query ."&tipo=hashtag'>Hashtags</a></li>
                    </ul>
                    <hr id='busqueda_hr'>";
            
            while ($fila_info_hashtag = mysqli_fetch_array($hashtagsRetornadosQuery)) 
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
        else if($tipo == "usuarios_nombres_y_grupos")
        {
            // + Verificar si los resultados fueron encontrados
            if(mysqli_num_rows($usuariosRetornadosQuery) + mysqli_num_rows($gruposRetornadosQuery) == 0)
            {
                echo "No se enontro nada que incluya la siguiente busqueda: " . $query;
            }
            else if(mysqli_num_rows($usuariosRetornadosQuery) != 1)
            {
                echo mysqli_num_rows($usuariosRetornadosQuery) + mysqli_num_rows($gruposRetornadosQuery) . " resultados encontrados: <br> <br>";
            }
            else
            {
                echo mysqli_num_rows($usuariosRetornadosQuery) + mysqli_num_rows($gruposRetornadosQuery) . " resultado encontrado: <br> <br>";
            }

            echo "  <p id='gris'>Intenta buscar: </p>";
            echo "  <ul>
                        <li><a href='search.php?query=" . $query ."&tipo=usuarios_nombres_y_grupos'>Nombres de usuario, nombres y grupos</a></li>
                        <li><a href='search.php?query=" . $query ."&tipo=nombre'>Nombres</a></li>
                        <li><a href='search.php?query=" . $query ."&tipo=username'>Nombres de Usuario</a></li>
                        <li><a href='search.php?query=" . $query ."&tipo=grupo'>Grupos</a></li>
                        <li><a href='search.php?query=" . $query ."&tipo=hashtag'>Hashtags</a></li>
                    </ul>
                    <hr id='busqueda_hr'>";

            while($fila = mysqli_fetch_array($usuariosRetornadosQuery))
            {
    
                $objeto_usuario = new Usuario($con, $id_usuario_loggeado);
    
                $boton_amigo = "";
                $boton_seguir = "";
                $amigos_mutuos = "";
    
                if($id_usuario_loggeado != $fila['id_usuario'])
                {
                    if($objeto_usuario->esAmigo($fila['id_usuario']))
                    {
                        $boton_amigo = "<input type='submit' name='amigo" . $fila['username'] . "' class='danger' value='Eliminar Amigo'>";
                    }
                    else if($objeto_usuario->checarSolicitudRecibida($fila['id_usuario']))
                    {
                        $boton_amigo = "<input type='submit' name='amigo" . $fila['username'] . "' class='warning' value='Responder Solicitud'>";
                    }
                    else if($objeto_usuario->checarSolicitudEnviada($fila['id_usuario']))
                    {
                        $boton_amigo = "<input type ='submit' class='default' value='Solicitud Enviada'>";
                    }
                    else
                    {
                        $boton_amigo = "<input type='submit' name='amigo" . $fila['username'] . "' class='success' value='Agregar Amigo'>";
                    }
                    if(!($objeto_usuario->esAmigo($fila['id_usuario'])))
                    {
                        if($objeto_usuario->esSeguidor($fila['id_usuario']))
                        {
                            $boton_seguir = "<input type='submit' name='seguir" . $fila['username'] . "' class='danger' value='Dejar de seguir'><br>";
                        }
                        else
                        {
                            $boton_seguir = "<input type='submit' name='seguir" . $fila['username'] . "' class='success' value='Seguir'><br>";
                        }
                    }
    
    
                    $amigos_mutuos = $objeto_usuario->obtenerAmigosMutuos(($fila['id_usuario']));
                    if ($amigos_mutuos != 1)
                    {
                        $amigos_mutuos .= " amigos en común";
                    }
                    else 
                    {
                        $amigos_mutuos .= " amigo en común";
                    }
                }
                // + Formularios de botones
                if(isset($_POST["amigo".$fila['username']]))
                {
                    if($objeto_usuario->esAmigo($fila['id_usuario']))
                    {
                        $objeto_usuario->eliminarAmigo($fila['id_usuario']);
                        // + Esto mandara a la misma pagina
                        header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                    }
                    else if($objeto_usuario->checarSolicitudRecibida($fila['id_usuario']))
                    {
                        header("Location: requests.php");
                    }
                    else if($objeto_usuario->checarSolicitudEnviada($fila['id_usuario']))
                    {
    
                    }
                    else
                    {
                        $objeto_usuario->enviarSolicitudAmistad($fila['id_usuario']);
                        header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                    }
                }
    
                if(isset($_POST["seguir".$fila['username']]))
                {
                    if($objeto_usuario->esSeguidor($fila['id_usuario']))
                    {
                        $objeto_usuario->dejarSeguir($fila['id_usuario']);
                        // + Esto mandara a la misma pagina
                        header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                    }
                    else
                    {
                        $objeto_usuario->seguirUsuario($fila['id_usuario']);
                        header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                    }
                }
                
    
                echo "<div class='resultado_busqueda'>
                        <div class='paginaBusquedaBotones'>
                            <form action='' method='POST'>
                                " . $boton_amigo . "
                                <br>
                                <br>
                                " . $boton_seguir . "
                                <br>
                            </form>
                        </div>
    
                        <div class='fotoPerfilResultado'>
                            <a href='" . $fila['username'] . "'><img src='" . $fila['foto_perfil'] . "' style='height: 100px;'></a> 
                        </div>
    
                            <a href='" . $fila['username'] . "'>
                                " . $fila['nombre'] . " " . $fila['apeP'] . " " . $fila['apeM'] . "
                            </a>
                            <p id='gris'>
                                <a href='" . $fila['username'] . "'>
                                " . $fila['username'] . " 
                                </a>
                            </p>
                            " . $amigos_mutuos . "
                            <br>
                    </div>
                    <hr id='busqueda_hr'>";
            } // Final del while 

            while ($fila = mysqli_fetch_array($gruposRetornadosQuery)) 
            {
                $id_grupo = $fila['id_grupo'];
                $nombre_grupo = $fila['nombre_grupo'];

                $objeto_grupo_usuario_loggeado = new Grupo($con, $id_usuario_loggeado);

                $boton_accion = "";
                if($objeto_grupo_usuario_loggeado->UsuarioPerteneceAlGrupo($id_grupo) == true)
                {
                    if($objeto_grupo_usuario_loggeado->EsUsuarioPropietario($id_grupo))
                    {
                        $boton_accion = "<button type='submit' class='danger' id='boton_eliminar_grupo$nombre_grupo'>Eliminar grupo</button><br>";
                    }
                    else if ($objeto_grupo_usuario_loggeado->UsuarioPerteneceAlGrupo($id_grupo) && ($objeto_grupo_usuario_loggeado->EsUsuarioPropietario($id_grupo) == false))
                    {
                        $boton_accion = "<button type='submit' class='danger' id='boton_salir_grupo$nombre_grupo'>Salir del grupo</button><br>";
                    }
                    ?>
                    <script>
                    // + Script deeliminar grupo
                    $(document).ready(function(){
                        $('#boton_eliminar_grupo<?php echo $nombre_grupo ?>').on('click', function() {
                            var id_usuario_propietario = <?php echo $objeto_grupo_usuario_loggeado->ObtenerIdUsuarioPropietario($id_grupo) ?>;
                            // - resultado -> Sera el resultadoado de lo que el usuario clickeo, si fue "si" o "no"
                            bootbox.confirm("¿Estas seguro que quieres eliminar este grupo?<br> No se podrá deshacer esta acción<br> Todas las publicaciones y comentarios se eliminaran", function(result) {
                                if(result == true)
                                {
                                    $.post("includes/form_handlers/delete_group.php?id_grupo=<?php echo $id_grupo; ?>&id_usuario_propietario=" + id_usuario_propietario, {resultado:result});
                                    window.location.href = 'search.php?query=<?php echo $query ?>&tipo=<?php echo $tipo?>';
                                }
                            });
                        });
                        $('#boton_salir_grupo<?php echo $nombre_grupo ?>').on('click', function() {
                            var id_usuario_loggeado = <?php echo $id_usuario_loggeado ?>;
                            // - resultado -> Sera el resultadoado de lo que el usuario clickeo, si fue "si" o "no"
                            bootbox.confirm("¿Estas seguro que quieres salir de este grupo?", function(result) {
                                if(result == true)
                                {
                                    $.post("includes/form_handlers/delete_member.php?id_grupo=<?php echo $id_grupo; ?>&id_miembro=" + id_usuario_loggeado, {resultado:result});
                                    window.location.href = 'search.php?query=<?php echo $query ?>&tipo=<?php echo $tipo?>';
                                }
                            });
                        });
                    });
                </script>    
                <?php     
                }
                else
                {
                    // + Checar si el usuario ya solicito unirse
                    $query_checar_si_hay_solicitud = mysqli_query($con, "SELECT * FROM solicitudes_de_grupo WHERE grupo_solicitado='$id_grupo' AND usuario_que_solicito_unirse='$id_usuario_loggeado'");
                    if(mysqli_num_rows($query_checar_si_hay_solicitud) > 0)
                    {
                        $boton_accion = "<form action='' method='POST'>
                                            <input type='submit' name='' value='Solicitud Enviada' class='default boton_solicitar_unirse'>
                                        </form>";
                    }
                    else
                    {
                        $boton_accion = "<form action='search.php?query=$query&tipo=$tipo' method='POST'>
                                            <input type='submit' name='solicitar_unirse_grupo$nombre_grupo' value='Solicitar Unirse' class='success boton_solicitar_unirse'>
                                        </form>";
                    }
                }

                $boton_ver = "<a href='" . $src_pagina . "groups/" . $fila['nombre_grupo'] . "'>
                                <button class='info'>Ver grupo</button>    
                            </a>";


                if(isset($_POST['solicitar_unirse_grupo'.$nombre_grupo]))
                {
                    $query_solicitud_grupo = mysqli_query($con, "INSERT INTO solicitudes_de_grupo VALUES ('', '$id_grupo', '$id_usuario_loggeado')");
                    header("Location: search.php?query=$query&tipo=$tipo");
                }

                echo "<div class='resultado_busqueda'>
                        <div class='paginaBusquedaBotones'>
                                $boton_accion
                                <br>
                            " . $boton_ver . "
                            <br>
                        </div>

                        <div class='imagenGrupoResultado'>
                            <a href='" . $src_pagina . "groups/" . $fila['nombre_grupo'] . "'>
                                <img src='" . $src_pagina . $fila['imagen_grupo'] . "' style='height: 100px;'>
                            </a>
                        </div>
                        <div class='liveSearchTexto'>
                            <a href='" . $src_pagina . "groups/" . $fila['nombre_grupo'] . "'>
                                " . $fila['nombre_grupo'] . "
                            </a>
                                <p style='margin: 0'>
                                    <a href='" . $src_pagina . "groups/" . $fila['nombre_grupo'] . "'>
                                        Grupo
                                    </a>
                                </p>
                        </div>
                    </div>
                    <hr id='busqueda_hr'>";
            }

        }
        else if($tipo == "nombre" || $tipo == "username")
        {
            // + Verificar si los resultados fueron encontrados
            if(mysqli_num_rows($usuariosRetornadosQuery) == 0)
            {
                echo "No se enontro nada que incluya la siguiente busqueda: " . $query;
            }
            else if(mysqli_num_rows($usuariosRetornadosQuery) != 1)
            {
                echo mysqli_num_rows($usuariosRetornadosQuery) . " resultados encontrados: <br> <br>";
            }
            else
            {
                echo mysqli_num_rows($usuariosRetornadosQuery) . " resultado encontrado: <br> <br>";
            }

            echo "<p id='gris'>Intenta buscar: </p>";
            echo "  <ul>
                        <li><a href='search.php?query=" . $query ."&tipo=usuarios_nombres_y_grupos'>Nombres de usuario, nombres y grupos</a></li>
                        <li><a href='search.php?query=" . $query ."&tipo=nombre'>Nombres</a></li>
                        <li><a href='search.php?query=" . $query ."&tipo=username'>Nombres de Usuario</a></li>
                        <li><a href='search.php?query=" . $query ."&tipo=grupo'>Grupos</a></li>
                        <li><a href='search.php?query=" . $query ."&tipo=hashtag'>Hashtags</a></li>
                    </ul>
                    <hr id='busqueda_hr'>";

            while($fila = mysqli_fetch_array($usuariosRetornadosQuery))
            {
    
                $objeto_usuario = new Usuario($con, $id_usuario_loggeado);
    
                $boton_amigo = "";
                $boton_seguir = "";
                $amigos_mutuos = "";
    
                if($id_usuario_loggeado != $fila['id_usuario'])
                {
                    if($objeto_usuario->esAmigo($fila['id_usuario']))
                    {
                        $boton_amigo = "<input type='submit' name='amigo" . $fila['username'] . "' class='danger' value='Eliminar Amigo'>";
                    }
                    else if($objeto_usuario->checarSolicitudRecibida($fila['id_usuario']))
                    {
                        $boton_amigo = "<input type='submit' name='amigo" . $fila['username'] . "' class='warning' value='Responder Solicitud'>";
                    }
                    else if($objeto_usuario->checarSolicitudEnviada($fila['id_usuario']))
                    {
                        $boton_amigo = "<input type ='submit' class='default' value='Solicitud Enviada'>";
                    }
                    else
                    {
                        $boton_amigo = "<input type='submit' name='amigo" . $fila['username'] . "' class='success' value='Agregar Amigo'>";
                    }
                    if(!($objeto_usuario->esAmigo($fila['id_usuario'])))
                    {
                        if($objeto_usuario->esSeguidor($fila['id_usuario']))
                        {
                            $boton_seguir = "<input type='submit' name='seguir" . $fila['username'] . "' class='danger' value='Dejar de seguir'><br>";
                        }
                        else
                        {
                            $boton_seguir = "<input type='submit' name='seguir" . $fila['username'] . "' class='success' value='Seguir'><br>";
                        }
                    }
    
    
                    $amigos_mutuos = $objeto_usuario->obtenerAmigosMutuos(($fila['id_usuario']));
                    if ($amigos_mutuos != 1)
                    {
                        $amigos_mutuos .= " amigos en común";
                    }
                    else 
                    {
                        $amigos_mutuos .= " amigo en común";
                    }
                }
                // + Formularios de botones
                if(isset($_POST["amigo".$fila['username']]))
                {
                    if($objeto_usuario->esAmigo($fila['id_usuario']))
                    {
                        $objeto_usuario->eliminarAmigo($fila['id_usuario']);
                        // + Esto mandara a la misma pagina
                        header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                    }
                    else if($objeto_usuario->checarSolicitudRecibida($fila['id_usuario']))
                    {
                        header("Location: requests.php");
                    }
                    else if($objeto_usuario->checarSolicitudEnviada($fila['id_usuario']))
                    {
    
                    }
                    else
                    {
                        $objeto_usuario->enviarSolicitudAmistad($fila['id_usuario']);
                        header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                    }
                }
    
                if(isset($_POST["seguir".$fila['username']]))
                {
                    if($objeto_usuario->esSeguidor($fila['id_usuario']))
                    {
                        $objeto_usuario->dejarSeguir($fila['id_usuario']);
                        // + Esto mandara a la misma pagina
                        header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                    }
                    else
                    {
                        $objeto_usuario->seguirUsuario($fila['id_usuario']);
                        header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                    }
                }
                
    
                echo "<div class='resultado_busqueda'>
                        <div class='paginaBusquedaBotones'>
                            <form action='' method='POST'>
                                " . $boton_amigo . "
                                <br>
                                <br>
                                " . $boton_seguir . "
                                <br>
                            </form>
                        </div>
    
                        <div class='fotoPerfilResultado'>
                            <a href='" . $fila['username'] . "'><img src='" . $fila['foto_perfil'] . "' style='height: 100px;'></a> 
                        </div>
    
                            <a href='" . $fila['username'] . "'>
                                " . $fila['nombre'] . " " . $fila['apeP'] . " " . $fila['apeM'] . "
                            </a>
                            <p id='gris'>
                                <a href='" . $fila['username'] . "'>
                                " . $fila['username'] . " 
                                </a>
                            </p>
                            " . $amigos_mutuos . "
                            <br>
                    </div>
                    <hr id='busqueda_hr'>";
            } // Final del while
        }
        else if($tipo == "grupo")
        {
            // + Verificar si los resultados fueron encontrados
            if(mysqli_num_rows($gruposRetornadosQuery) == 0)
            {
                echo "No se enontro nada que incluya la siguiente busqueda: " . $query;
            }
            else if(mysqli_num_rows($gruposRetornadosQuery) != 1)
            {
                echo mysqli_num_rows($gruposRetornadosQuery) . " resultados encontrados: <br> <br>";
            }
            else
            {
                echo mysqli_num_rows($gruposRetornadosQuery) . " resultado encontrado: <br> <br>";
            }

            echo "<p id='gris'>Intenta buscar: </p>";
            echo "  <ul>
                        <li><a href='search.php?query=" . $query ."&tipo=usuarios_nombres_y_grupos'>Nombres de usuario, nombres y grupos</a></li>
                        <li><a href='search.php?query=" . $query ."&tipo=nombre'>Nombres</a></li>
                        <li><a href='search.php?query=" . $query ."&tipo=username'>Nombres de Usuario</a></li>
                        <li><a href='search.php?query=" . $query ."&tipo=grupo'>Grupos</a></li>
                        <li><a href='search.php?query=" . $query ."&tipo=hashtag'>Hashtags</a></li>
                    </ul>
                    <hr id='busqueda_hr'>";

            while ($fila = mysqli_fetch_array($gruposRetornadosQuery)) 
            {
                $id_grupo = $fila['id_grupo'];
                $nombre_grupo = $fila['nombre_grupo'];

                $objeto_grupo_usuario_loggeado = new Grupo($con, $id_usuario_loggeado);

                $boton_accion = "";
                if($objeto_grupo_usuario_loggeado->UsuarioPerteneceAlGrupo($id_grupo) == true)
                {
                    if($objeto_grupo_usuario_loggeado->EsUsuarioPropietario($id_grupo))
                    {
                        $boton_accion = "<button type='submit' class='danger' id='boton_eliminar_grupo$nombre_grupo'>Eliminar grupo</button><br>";
                    }
                    else if ($objeto_grupo_usuario_loggeado->UsuarioPerteneceAlGrupo($id_grupo) && ($objeto_grupo_usuario_loggeado->EsUsuarioPropietario($id_grupo) == false))
                    {
                        $boton_accion = "<button type='submit' class='danger' id='boton_salir_grupo$nombre_grupo'>Salir del grupo</button><br>";
                    }
                    ?>
                    <script>
                    // + Script deeliminar grupo
                    $(document).ready(function(){
                        $('#boton_eliminar_grupo<?php echo $nombre_grupo ?>').on('click', function() {
                            var id_usuario_propietario = <?php echo $objeto_grupo_usuario_loggeado->ObtenerIdUsuarioPropietario($id_grupo) ?>;
                            // - resultado -> Sera el resultadoado de lo que el usuario clickeo, si fue "si" o "no"
                            bootbox.confirm("¿Estas seguro que quieres eliminar este grupo?<br> No se podrá deshacer esta acción<br> Todas las publicaciones y comentarios se eliminaran", function(result) {
                                if(result == true)
                                {
                                    $.post("includes/form_handlers/delete_group.php?id_grupo=<?php echo $id_grupo; ?>&id_usuario_propietario=" + id_usuario_propietario, {resultado:result});
                                    window.location.href = 'search.php?query=<?php echo $query ?>&tipo=<?php echo $tipo?>';
                                }
                            });
                        });
                        $('#boton_salir_grupo<?php echo $nombre_grupo ?>').on('click', function() {
                            var id_usuario_loggeado = <?php echo $id_usuario_loggeado ?>;
                            // - resultado -> Sera el resultadoado de lo que el usuario clickeo, si fue "si" o "no"
                            bootbox.confirm("¿Estas seguro que quieres salir de este grupo?", function(result) {
                                if(result == true)
                                {
                                    $.post("includes/form_handlers/delete_member.php?id_grupo=<?php echo $id_grupo; ?>&id_miembro=" + id_usuario_loggeado, {resultado:result});
                                    window.location.href = 'search.php?query=<?php echo $query ?>&tipo=<?php echo $tipo?>';
                                }
                            });
                        });
                    });
                </script>    
                <?php     
                }
                else
                {
                    // + Checar si el usuario ya solicito unirse
                    $query_checar_si_hay_solicitud = mysqli_query($con, "SELECT * FROM solicitudes_de_grupo WHERE grupo_solicitado='$id_grupo' AND usuario_que_solicito_unirse='$id_usuario_loggeado'");
                    if(mysqli_num_rows($query_checar_si_hay_solicitud) > 0)
                    {
                        $boton_accion = "<form action='' method='POST'>
                                            <input type='submit' name='' value='Solicitud Enviada' class='default boton_solicitar_unirse'>
                                        </form>";
                    }
                    else
                    {
                        $boton_accion = "<form action='search.php?query=$query&tipo=$tipo' method='POST'>
                                            <input type='submit' name='solicitar_unirse_grupo$nombre_grupo' value='Solicitar Unirse' class='success boton_solicitar_unirse'>
                                        </form>";
                    }
                }

                $boton_ver = "<a href='" . $src_pagina . "groups/" . $fila['nombre_grupo'] . "'>
                                <button class='info'>Ver grupo</button>    
                            </a>";


                if(isset($_POST['solicitar_unirse_grupo'.$nombre_grupo]))
                {
                    $query_solicitud_grupo = mysqli_query($con, "INSERT INTO solicitudes_de_grupo VALUES ('', '$id_grupo', '$id_usuario_loggeado')");
                    header("Location: search.php?query=$query&tipo=$tipo");
                }

                echo "<div class='resultado_busqueda'>
                        <div class='paginaBusquedaBotones'>
                                $boton_accion
                                <br>
                            " . $boton_ver . "
                            <br>
                        </div>

                        <div class='imagenGrupoResultado'>
                            <a href='" . $src_pagina . "groups/" . $fila['nombre_grupo'] . "'>
                                <img src='" . $src_pagina . $fila['imagen_grupo'] . "' style='height: 100px;'>
                            </a>
                        </div>
                        <div class='liveSearchTexto'>
                            <a href='" . $src_pagina . "groups/" . $fila['nombre_grupo'] . "'>
                                " . $fila['nombre_grupo'] . "
                            </a>
                                <p style='margin: 0'>
                                    <a href='" . $src_pagina . "groups/" . $fila['nombre_grupo'] . "'>
                                        Grupo
                                    </a>
                                </p>
                        </div>
                    </div>
                    <hr id='busqueda_hr'>";
            }
        }
    }

    ?>

</div>