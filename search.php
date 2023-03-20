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
}
else
{
    $tipo = "nombre";
}
?>

<div class="columna_principal" id="columna_principal">
    <?php
    if($query == "")
    {
        echo "Debes ingresar algo en la barra de busqueda";
    }
    else
    {
        // + Si el tipo de query es para un nombre de usuario
        if($tipo == "username")
        {
            $usuariosRetornadosQuery = mysqli_query($con, "SELECT * FROM usuarios WHERE username LIKE '$query%' AND usuario_cerrado='no'");
        }
        else
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

        // + Verificar si los resultados fueron encontrados
        if(mysqli_num_rows($usuariosRetornadosQuery) == 0)
        {
            echo "No se enontro nada que incluya la siguiente busqueda: " . $query;
        }
        else
        {
            echo mysqli_num_rows($usuariosRetornadosQuery) . " resultados encontrados: <br> <br>";
        }

        echo "<p id='gris'>Intenta buscar: </p>";
        echo "<a href='search.php?query=" . $query ."&type=name'>Nombres</a>, <a href='search.php?query=" . $query ."&type=username'>Nombres de Usuario</a><br><br><hr id='busqueda_hr'>";

        while($fila = mysqli_fetch_array($usuariosRetornadosQuery))
        {

            $objeto_usuario = new Usuario($con, $id_usuario_loggeado);

            $boton = "";
            $amigos_mutuos = "";

            if($id_usuario_loggeado != $fila['id_usuario'])
            {
                if($objeto_usuario->esAmigo($fila['username']))
                {
                    $boton = "<input type='submit' name='" . $fila['username'] . "' class='danger' value='Eliminar Amigo'>";
                }
                else if($objeto_usuario->checarSolicitudRecibida($fila['id_usuario']))
                {
                    $boton = "<input type='submit' name='" . $fila['username'] . "' class='warning' value='Responder Solicitud'>";
                }
                else if($objeto_usuario->checarSolicitudEnviada($fila['id_usuario']))
                {
                    $boton = "<input type ='submit' class='default' value='Solicitud Enviada'>";
                }
                else
                {
                    $boton = "<input type='submit' name='" . $fila['username'] . "' class='success' value='Agregar Amigo'>";
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
            if(isset($_POST[$fila['username']]))
            {
                if($objeto_usuario->esAmigo($fila['username']))
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
            

            echo "<div class='resultado_busqueda'>
                    <div class='paginaBusquedaBotones'>
                        <form action='' method='POST'>
                            " . $boton . "
                            <br>
                        </form>
                    </div>

                    <div class='fotoPerfilResultado'>
                        <a href='" . $fila['username'] . "'><img src='" . $fila['foto_perfil'] . "' style='height: 100px;'></a> 
                    </div>

                        <a href='" . $fila['username'] . "'>" . $fila['nombre'] . " " . $fila['apeP'] . " " . $fila['apeM'] . "
                        <p id='gris'>" . $fila['username'] . " </p>
                        </a>
                        " . $amigos_mutuos . "
                        <br>
                </div>
                <hr id='busqueda_hr'>";
        } // Final del while
    }

    ?>

</div>