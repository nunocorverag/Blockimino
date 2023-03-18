<?php
    require 'config/config.php';
    include("includes/classes/Usuario.php");
    include("includes/classes/Publicacion.php");
    include("includes/classes/Notificacion.php");

    if (isset($_SESSION['username']) && $_SESSION['tipo'] == "normal" || $_SESSION['tipo'] == "moderador" || $_SESSION['tipo'] == "administrador") {
        $usuario_loggeado = $_SESSION['username'];
        $id_usuario_loggeado = $_SESSION['id_usuario'];
        $query_detalles_usuario = mysqli_query($con, "SELECT * FROM usuarios WHERE username='$usuario_loggeado'");
        $usuario = mysqli_fetch_array($query_detalles_usuario);
        $tipo_usuario = $_SESSION['tipo'];
    } else {
        header("Location: index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <!-- Incluimos el archivo en donde diseñaremos nuestro css -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- // + Este bloque de estilos es exclusivo de este documento -->
    <!-- // ! Mas adelante cambiar a mi propio estilo -->
    <style type="text/css">
        /* // $ El * Significa todo el documento */
        *{
            font-size: 12;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>

    <script>
        function toggle()
        {
            var element = document.getElementById("seccion_comentarios");
            // + Cuando hagamos click, si el elemento se encuentra oculto, lo mostramos y si se esta mostrando, lo oculpamos
            if(element.style.display == "block")
            {
                element.style.display = "none";
            }
            else
            {
                element.style.display = "block";
            }
        }
    </script>
        <?php
        // $ $_GET envia la informacion en la propia url, esto para obtener el id de la publicacion y enviarlo a la url
        if(isset($_GET['id_publicacion']))
        {
            $id_publicacion = $_GET['id_publicacion'];
        }

        // + Hacemos query y seleccionamos publicado por y publicado para de una publicacion de cierta publicacion
        $query_usuario = mysqli_query($con, "SELECT publicado_por, publicado_para FROM publicaciones WHERE id_publicacion='$id_publicacion'");
        $fila = mysqli_fetch_array($query_usuario);
        // - El comentado_para sera para e usuario que publico el post
        $comentado_por = $id_usuario_loggeado;
        $comentado_para = $fila['publicado_por'];
        $publicado_para = $fila['publicado_para'];

        // + Si esta funcion es verdadera, significa que se esta intentando postear un comentario entonces:
        if(isset($_POST['publicarComentario' . $id_publicacion]))
        {
            $cuerpo_comentario = $_POST['cuerpo_comentario'];
            $cuerpo_comentario = mysqli_escape_string($con, $cuerpo_comentario);
            $fecha_comentado = date("Y-m-d H:i:s");
            $publicacion_comentada = $id_publicacion;
            $insertar_comentario = mysqli_query($con, "INSERT INTO comentarios VALUES ('', '$cuerpo_comentario', '$comentado_por', '$comentado_para', '$fecha_comentado', 'no', '$publicacion_comentada')");

            // + Insertar notificacion
            // + Si el usuario no realizo un comentario en una publicacion propia
            if ($comentado_para != $id_usuario_loggeado)
            {
                $notificacion = new Notificacion($con, $id_usuario_loggeado);
                $notificacion->insertarNotificacion($id_publicacion, $comentado_para, "comentario");
            }
            
            // + Si el usuario no relizo un comentario en una publicacion propia y es una publicacion de perfil (Que no realizo el mismo usuario, o sea que otro usuario realizo una publicacion en el perfil del usuario)
            if($publicado_para != NULL && $publicado_para != $id_usuario_loggeado)
            {
                $notificacion = new Notificacion($con, $id_usuario_loggeado);
                $notificacion->insertarNotificacion($id_publicacion, $publicado_para, "comentario_perfil");
            }

            // + Si el usuario realizo un comentario donde otro usuario comento
            $query_obtener_comentadores = mysqli_query($con, "SELECT * FROM comentarios WHERE publicacion_comentada='$id_publicacion'");
            $usuarios_notificados = array();
            while ($fila = mysqli_fetch_array($query_obtener_comentadores))
            {
                // + Si el usuario que comento no es la persona que tiene el post en el perfil
                // + Si el usuario que comento, no lo hizo en su propia publicacion
                // + Si no es el usuario que acaba de comentar
                // + Si el usuario todavia no fue notificado
                if ($fila['comentado_por'] != $publicado_para && $fila['comentado_por'] != $comentado_para && $fila['comentado_por'] != $id_usuario_loggeado && !in_array($fila['comentado_por'], $usuarios_notificados))
                {
                    $notificacion = new Notificacion($con, $id_usuario_loggeado);
                    $notificacion->insertarNotificacion($id_publicacion, $fila['comentado_por'], "comentario_donde_comentaste");

                    array_push($usuarios_notificados, $fila['comentado_por']);
                }
            }

            echo "<p>Comentario Publicado!</p>";
        }
        ?>

        <!-- Este form enviara a la misma pagina un id para acceder a la publicacion-->
        <form action="comment_frame.php?id_publicacion=<?php echo $id_publicacion; ?>" id="formulario_comentarios" name="publicarComentario<?php echo $id_publicacion; ?>" method="POST" >
            <textarea name="cuerpo_comentario"></textarea>
            <input type="submit" name="publicarComentario<?php echo $id_publicacion; ?>" value="Publicar">
        </form>

        <!-- Cargar los comentarios -->
        <?php
        $obtener_comentarios = mysqli_query($con, "SELECT * FROM comentarios WHERE publicacion_comentada='$id_publicacion' ORDER BY id_comentario ASC");
        $cantidad_comentarios = mysqli_num_rows($obtener_comentarios);
        if ($cantidad_comentarios != 0)
        {
            // + Mostramos cada comentario
            while($comentario = mysqli_fetch_array($obtener_comentarios))
            {
                $cuerpo_comentario = $comentario['cuerpo_comentario'];
                $comentado_para = $comentario['comentado_para'];
                $comentado_por = $comentario['comentado_por'];
                // ! no se si esta variable genere error con la de arriba fecha_comentado
                $fecha_comentado = $comentario['fecha_comentado'];
                $eliminado = $comentario['eliminado'];

                #region Periodo de tiempo de los comentarios
                // - Guardamos la hora y fecha actuales
                $tiempo_actual = date("Y-m-d H:i:s");
                // - Guardamos la hora y fecha actuales en el que se realizo la publicacion
                $fecha_comienzo = new DateTime($fecha_comentado);
                // - Guardamos la hora y fecha actuales
                $fecha_final = new DateTime($tiempo_actual);
                // - Realizamos una diferencia de tiempos de la fecha inicial, con la actual para saber cuanto tiempo lleva la publicacion publicada
                $intervalo = $fecha_comienzo->diff($fecha_final);
                // + Si el intervalo es 1 o mas años
                if($intervalo->y >= 1)
                {
                    //Un año de antiguedad
                    if($intervalo->y == 1)
                    {
                        $mensaje_tiempo = $intervalo->y . " año atrás";
                    }
                    //Más de un año de antiguedad
                    else
                    {
                        $mensaje_tiempo = $intervalo->y . " años atrás";
                    }
                }
                // + Si el intervalo es 1 o mas de 1 mes atras, pero menos de un año
                else if($intervalo->m >= 1)
                {
                    // + Checamos los dias 
                    // 0 dias
                    if($intervalo->d == 0)
                    {
                        $dias = " atrás";
                    }
                    // 1 dia
                    else if($intervalo->d == 1)
                    {
                        $dias = $intervalo->d. "día atrás";
                    }
                    //Mas de 1 dia
                    else 
                    {
                        $dias = $intervalo->d . " días atrás";
                    }

                    //1 mes
                    if($intervalo-> m == 1)
                    {
                        $mensaje_tiempo = $intervalo->m . "mes" . $dias;
                    }
                    //Mas de 1 mes
                    else
                    {
                        $mensaje_tiempo = $intervalo->m . "meses" . $dias;
                    }
                }
                // + Si el intervalo es 1 o mas dias atras, pero menos que un mes
                else if($intervalo->d >= 1)
                {
                    //1 dia
                    if($intervalo->d == 1)
                    {
                        $mensaje_tiempo = "ayer";
                    }
                    //Mas de un dia
                    else 
                    {
                        $mensaje_tiempo = $intervalo->d . " días atrás";
                    }
                }
                // + Si el intervalo es 1 o mas horas atras, pero menos que un dia
                else if($intervalo->h >= 1)
                {
                    //1 hora atras
                    if($intervalo->h == 1)
                    {
                        $mensaje_tiempo = $intervalo->h . " hora atrás";
                    }
                    //Mas de una hora
                    else 
                    {
                        $mensaje_tiempo = $intervalo->h . " horas atrás";
                    }
                }
                // + Si el intervalo es de 1 minuto o mas atras, pero menos que una hora
                else if($intervalo->i >= 1)
                {
                    //1 minuto atras
                    if($intervalo->i == 1)
                    {
                        $mensaje_tiempo = $intervalo->i . " minuto atrás";
                    }
                    //Mas de un minuto
                    else 
                    {
                        $mensaje_tiempo = $intervalo->i . " minutos atrás";
                    }
                }
                // + Si el intervalo es de 1 segundo o mas atras, pero menos que un minuto
                else
                {
                    //Menos que 30 segundos
                    if($intervalo->s < 30)
                    {
                        $mensaje_tiempo = "Justo ahora";
                    }
                    //30 segundos o mas
                    else 
                    {
                        $mensaje_tiempo = "Hace unos segundos";
                    }
                }
                #endregion

                // + Creamos un objeto usuario con el usuario que realizo el comentario para demostrar los detalles del usuario y el comentario a continuacion
                $objeto_usuario = new Usuario($con, $comentado_por);
                ?>
                <div class="seccion_comentarios">
                    <!-- target="_parent" Es para que mande el link al perfil actual y no me muestre la pantalla de iframe -->
                    <!-- // + Si el usuario hace click en la imagen, lo enviara al perfil del que realizo el comentario -->
                    <a href="<?php echo $objeto_usuario->obtenerNombreUsuario(); ?>" target="_parent">
                        <!-- // + Mostramos la foto de perfil del usuario que realizo el comentario, ademas de mostrar el nombre del usuario al pasar el cursor por la foto de perfil-->
                        <img src="<?php echo $objeto_usuario->obtenerFotoPerfil(); ?>" title="<?php echo $objeto_usuario->obtenerNombreUsuario(); ?>" style="float:left;" height="34">
                    </a>
                    <!-- // + Mostramos el nombre completo de la persona que comento, si el usuario hace click aqui, tambien los mandara a la pagina del perfil del usuario que comento-->
                    <a href="<?php echo $objeto_usuario->obtenerNombreUsuario(); ?>" target="_parent">
                            <b><?php echo $objeto_usuario->obtenerNombreCompleto(); ?></b>
                    </a>
                    <!-- // + Realizamos algunos espacios entre el comentario y mostramos el tiempo que ha pasado desde que se realizo el comentario -->
                    <!-- // + Posteriormente, un salto de linea y mostramos el cuerpo del comentario -->
                    &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $mensaje_tiempo . "<br>" . $cuerpo_comentario; ?>
                    <hr>
                </div>
                <?php
            }
        }
        else {
            // + Si no hay comentarios para mostrar, entonces mostraremos un mensaje
            echo "<center><br><br> No hay comentarios para mostrar! </center>";
        }
        ?>
</body>
</html>