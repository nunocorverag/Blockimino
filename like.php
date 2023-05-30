<?php
require 'config/config.php';
include("includes/classes/Usuario.php");
include("includes/classes/Publicacion.php");
include("includes/classes/Notificacion.php");

if(isset($_SESSION['id_usuario']))
{
    // - Esta variable guarda el id del usuario
    $id_usuario_loggeado = $_SESSION['id_usuario'];

    // - Guardamos en esta variable la query de todos los datos del usuario loggeado
    $query_detalles_usuario = mysqli_query($con, "SELECT * FROM usuarios WHERE id_usuario='$id_usuario_loggeado'");
    // - Guardamos en esta variable 
    $fila_detalles_usuario = mysqli_fetch_array($query_detalles_usuario);
    // - Esta variable guardara el nombre de usuario para poder hacer querys mas adelante
    $usuario_loggeado = $fila_detalles_usuario['username'];

    $query_verificar_que_usuario_no_este_sancionado = mysqli_query($con, "SELECT * FROM sanciones WHERE id_usuario_sancionado='$id_usuario_loggeado' AND sancion_eliminada='no'");
    if(mysqli_num_rows($query_verificar_que_usuario_no_este_sancionado) > 0)
    {
        header("Location: " . dirname($_SERVER['PHP_SELF']) . "/sanctioned.php?username=" . $usuario_loggeado);
    }
    else
    {            
        // RF16 Al iniciar sesion se detecta el tipo de usuario
        $tipo_usuario = $fila_detalles_usuario['tipo'];
    }
}
// + Si no encuentra un usuario loggeado, lo va a regresar a la pagina para crear usuario / iniciar sesion
else 
{
    header("Location: " . dirname($_SERVER['PHP_SELF']) . "/index.php");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title></title>
    <!-- Incluimos el archivo en donde diseñaremos nuestro css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Incluimos fontawesome para tener algunos iconos con los cuales trabajar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

    <!-- // + Este bloque de estilos es exclusivo de este documento -->
    <!-- // ! Mas adelante cambiar a mi propio estilo -->
    <style type="text/css">
        * {
            font-family: Arial, Helvetica, sans-serif;
        }
        body {
            background-color: #FFFFFF;
        }
        form{
            position: relative;
            top: 2px;
        }
        form input[type="submit"]{
            font-size: 20px;
            cursor: pointer;
            background-color: transparent;
            border: none;
            color: #20AAE5;
        }

    </style>
    <?php
        if(isset($_GET['id_publicacion']))
        {
            $id_publicacion = $_GET['id_publicacion'];
        }

        // + Obtiene los likes y el id de quien realizo la publicacion
        $obtener_likes = mysqli_query($con, "SELECT likes, publicado_por FROM publicaciones WHERE id_publicacion='$id_publicacion'");
        $fila = mysqli_fetch_array($obtener_likes);
        // - Obtiene los likes totales
        $likes_totales = $fila['likes'];
        $usuario_likeado = $fila['publicado_por'];

        // + Va a regresar toda la informacion del usuario que realizo la publicacion
        $query_detalles_usuario = mysqli_query($con, "SELECT * FROM usuarios WHERE id_usuario='$usuario_likeado'");
        $fila = mysqli_fetch_array($query_detalles_usuario);
        $likes_usuario_totales = $fila['num_likes'];

        //Boton de like
        if(isset($_POST['boton_likear']))
        {
            // + Aumentamos los likes en la publicacion
            $likes_totales ++;
            $actualizar_likes = mysqli_query($con, "UPDATE publicaciones SET likes='$likes_totales' WHERE id_publicacion='$id_publicacion'");
            // + Aumentamos los likes que ha dado el usuario
            $likes_usuario_totales++;
            $likes_usuario = mysqli_query($con, "UPDATE usuarios SET num_likes='$likes_usuario_totales' WHERE id_usuario=$usuario_likeado");
            $insertar_en_tabla_likes = mysqli_query($con, "INSERT INTO likes VALUES ('', $id_usuario_loggeado, '$id_publicacion')");

            // + Notificaciones
            if ($usuario_likeado != $id_usuario_loggeado)
            {
                $notificacion = new Notificacion($con, $id_usuario_loggeado);
                // ! LIKE ESTA FALLANDO EN ESTA linea
                $notificacion->insertarNotificacion($id_publicacion, $usuario_likeado, "like");
            }

            // + Aumentar interes
            $query_info_publicacion = mysqli_query($con, "SELECT hashtags_publicacion FROM publicaciones WHERE id_publicacion='$id_publicacion'");
            $fila_info_publicacion = mysqli_fetch_array($query_info_publicacion);
            $hashtags = $fila_info_publicacion['hashtags_publicacion'];
            $hashtags = explode(",", $hashtags);
            foreach ($hashtags as $hashtag) {
                if($hashtag != "")
                {
                    // + query insertar interes a la tabla de intereses 
                    $query_verificar_interes = mysqli_query($con, "SELECT * FROM temas_interes WHERE id_hashtag_interes='$hashtag' AND id_usuario_interesado='$id_usuario_loggeado'");
                    if(mysqli_num_rows($query_verificar_interes) > 0)
                    {
                        $fila_info_interes = mysqli_fetch_array($query_verificar_interes);
                        $cantidad_interes = $fila_info_interes['cantidad_interes'];
                        if(!($cantidad_interes > 500))
                        {
                            $query_agregar_cantidad_interes = mysqli_query($con, "UPDATE temas_interes SET cantidad_interes=cantidad_interes+1 WHERE id_hashtag_interes='$hashtag' AND id_usuario_interesado='$id_usuario_loggeado'");
                        }
                    }
                    else
                    {
                        $query_insertar_interes = mysqli_query($con, "INSERT INTO temas_interes VALUES ('', '$id_usuario_loggeado', '$hashtag', '1')");
                    }
                }
            }
        }


        //Boton de unlike
        if(isset($_POST['boton_deslikear']))
        {
            // + Disminuimos los likes en la publicacion
            $likes_totales --;
            $actualizar_likes = mysqli_query($con, "UPDATE publicaciones SET likes='$likes_totales' WHERE id_publicacion='$id_publicacion'");
            // + Disminuimos los likes que ha dado el usuario
            $likes_usuario_totales--;
            $likes_usuario = mysqli_query($con, "UPDATE usuarios SET num_likes='$likes_usuario_totales' WHERE id_usuario=$usuario_likeado");
            $eliminar_de_tabla_likes = mysqli_query($con, "DELETE FROM likes WHERE likeado_por='$id_usuario_loggeado' AND publicacion_likeada='$id_publicacion'");

            // + Decrementar interes
            $query_info_publicacion = mysqli_query($con, "SELECT hashtags_publicacion FROM publicaciones WHERE id_publicacion='$id_publicacion'");
            $fila_info_publicacion = mysqli_fetch_array($query_info_publicacion);
            $hashtags = $fila_info_publicacion['hashtags_publicacion'];
            $hashtags = explode(",", $hashtags);
            foreach ($hashtags as $hashtag) {
                if($hashtag != "")
                {
                    // + query insertar interes a la tabla de intereses 
                    $query_verificar_interes = mysqli_query($con, "SELECT * FROM temas_interes WHERE id_hashtag_interes='$hashtag' AND id_usuario_interesado='$id_usuario_loggeado'");
                    if(mysqli_num_rows($query_verificar_interes) > 0)
                    {
                        $query_agregar_cantidad_interes = mysqli_query($con, "UPDATE temas_interes SET cantidad_interes=cantidad_interes-1 WHERE id_hashtag_interes='$hashtag' AND id_usuario_interesado='$id_usuario_loggeado'");
                        $fila_interes = mysqli_fetch_array($query_verificar_interes);
                        $cantidad_interes = $fila_interes['cantidad_interes'];
                        if($cantidad_interes <= 1)
                        {
                            $query_eliminar_interes = mysqli_query($con, "DELETE FROM temas_interes WHERE id_hashtag_interes='$hashtag' AND id_usuario_interesado='$id_usuario_loggeado'");
                        }
                    }
                }
            }

        }

        //Vetificar likes anteriores
        // + Obtenmos lo detalles si el usuario likeo la publicacion o no
        $query_verificar_si_likeo = mysqli_query($con, "SELECT * FROM likes WHERE likeado_por='$id_usuario_loggeado' AND publicacion_likeada='$id_publicacion'");
        $numero_filas = mysqli_num_rows($query_verificar_si_likeo);
        if($numero_filas > 0)
        {
            echo '<form action="like.php?id_publicacion=' . $id_publicacion . '"method="POST" id="deslikear">     
                    <input type="submit" class="fa fa-thumbs-up" name="boton_deslikear" value="&#xf164">
                    <div class="numero_likes">
                        '. $likes_totales .' Likes
                    </div>
                </form>
            '; //Simbolo de deslikear
        }
        else 
        {
            echo '<form action="like.php?id_publicacion=' . $id_publicacion . '"method="POST">
                    <input type="submit" class="fa-regular fa-thumbs-up" name="boton_likear" value="&#xf087">
                    <div class="numero_likes">
                        '. $likes_totales .' Likes
                    </div>
                </form>
            '; //Simbolo de like
        }
    ?>

</body>
</html>