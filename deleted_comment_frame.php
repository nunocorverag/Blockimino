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

    $tipo_usuario = $fila_detalles_usuario['tipo'];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- JavaScript -->
    <!-- Incluimos jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <!-- Incluimos bootstrap de javastcript Javascript-->
    <script src="assets/js/bootstrap.js"></script>

    <script src="assets/js/bootbox.js"></script>


    <!-- Incluimos el archivo en donde diseñaremos nuestro css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Incluimos fontawesome para tener algunos iconos con los cuales trabajar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Incluimos bootstrap para css -->
    <link rel="stylesheet" href="assets/css/bootstrap.css">
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
        ?>


        <!-- Cargar los comentarios -->
        <?php
        $obtener_comentarios = mysqli_query($con, "SELECT * FROM comentarios WHERE (eliminado='si' AND publicacion_comentada='$id_publicacion') ORDER BY id_comentario ASC");
        $cantidad_comentarios = mysqli_num_rows($obtener_comentarios);
        if ($cantidad_comentarios != 0)
        {
            // + Mostramos cada comentario
            while($comentario = mysqli_fetch_array($obtener_comentarios))
            {
                $id_comentario = $comentario['id_comentario'];
                $cuerpo_comentario = $comentario['cuerpo_comentario'];
                $comentado_para = $comentario['comentado_para'];
                $comentado_por = $comentario['comentado_por'];
                // ! no se si esta variable genere error con la de arriba fecha_comentado
                $fecha_comentado = $comentario['fecha_comentado'];
                $eliminado = $comentario['eliminado'];

                $query_obtener_tipo_usuario_comentado_por = mysqli_query($con, "SELECT tipo FROM usuarios WHERE id_usuario='$comentado_por'");
                $fila_tipo_comentado = mysqli_fetch_array($query_obtener_tipo_usuario_comentado_por);
                $tipo_usuario_comentado_por = $fila_tipo_comentado['tipo'];


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
                        $dias = $intervalo->d. " día atrás";
                    }
                    //Mas de 1 dia
                    else 
                    {
                        $dias = $intervalo->d . " días atrás";
                    }

                    //1 mes
                    if($intervalo-> m == 1)
                    {
                        $mensaje_tiempo = $intervalo->m . " mes " . $dias;
                    }
                    //Mas de 1 mes
                    else
                    {
                        $mensaje_tiempo = $intervalo->m . " meses " . $dias;
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