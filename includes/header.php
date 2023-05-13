<?php
require 'config/config.php';
include("includes/classes/Usuario.php");
include("includes/classes/Publicacion.php");
include("includes/classes/Mensaje.php");
include("includes/classes/Notificacion.php");
include("includes/classes/Grupo.php");

// + Si ya existe un usuario loggeado, entonces:
// RF14 - 16 El tipo de usuario puede ser moderador/administrador/normal -> Si es otro diferente, no dejara iniciar sesion
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

    $query_verificar_que_usuario_no_este_sancionado = mysqli_query($con, "SELECT * FROM sanciones WHERE id_usuario_sancionado='$id_usuario_loggeado'");
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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>

    <!-- JavaScript -->
    <!-- Incluimos jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <!-- Incluimos bootstrap de javastcript Javascript-->
    <script src="<?php echo dirname($_SERVER['PHP_SELF']) . '/assets/js/bootstrap.js'; ?>"></script>
    <script src="<?php echo dirname($_SERVER['PHP_SELF']) . '/assets/js/bootbox.js'; ?>"></script>
    <script src="<?php echo dirname($_SERVER['PHP_SELF']) . '/assets/js/blockimino.js'; ?>"></script>

    <!-- //! Falta explicar estos de abajo -->
    <script src="<?php echo dirname($_SERVER['PHP_SELF']) . '/assets/js/jquery.jcrop.js'; ?>"></script>
    <script src="<?php echo dirname($_SERVER['PHP_SELF']) . '/assets/js/Jcrop_bits.js'; ?>"></script>

    <!-- CSS -->
    <!-- Incluimos fontawesome para tener algunos iconos con los cuales trabajar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Incluimos bootstrap para css -->
    <link rel="stylesheet" href="<?php echo dirname($_SERVER['PHP_SELF']) . '/assets/css/bootstrap.css'; ?>">
    <!-- Incluimos el archivo en donde diseñaremos nuestro css -->
    <link rel="stylesheet" href="<?php echo dirname($_SERVER['PHP_SELF']) . '/assets/css/style.css'; ?>">

    <!-- //! Falta explicar este de abajo -->
    <link rel="stylesheet" href="<?php echo dirname($_SERVER['PHP_SELF']) . '/assets/css/jquery.Jcrop.css" type="text/css'; ?>"/>

</head>
<body>

     <div class="barra_superior">
        <div class="logo">
            <a href="<?php echo dirname($_SERVER['PHP_SELF']) ?>/home.php">Blockimino</a>
        </div>

        <div class="busqueda">
            <form action="<?php echo dirname($_SERVER['PHP_SELF']) ?>/search.php" method="GET" name="formulario_busqueda">
                <input type="text" onkeyup="obtenerLiveSearchUsuarios(this.value, '<?php echo $id_usuario_loggeado?>')" name="query" placeholder="Buscar algo..." autocomplete="off" id="input_busqueda_texto">
                <input type="hidden" name="tipo" id="tipo">
                <div class="contenedor_boton">
                    <i class="fa-solid fa-magnifying-glass"></i>                
                </div>
            </form>

            <script>
                // Obtener referencia al campo de búsqueda
                const input_busqueda_texto = document.getElementById('input_busqueda_texto');
                const tipo = document.getElementById('tipo');

                // Escuchar el evento keyup del campo de búsqueda
                input_busqueda_texto.addEventListener('keyup', function(event) {
                    // Obtener la cadena de búsqueda y comprobar si empieza con #
                    const query = input_busqueda_texto.value.trim();
                    if (query.startsWith('#')) {
                        // Si empieza con #, establecer el valor del input tipo en 'hashtag'
                        tipo.value = 'hashtag';
                    } else {
                        // Si no empieza con #, establecer el valor del input tipo en ''
                        tipo.value = 'usuarios_nombres_y_grupos';
                    }
                });
            </script>

            <div class="resultados_busqueda">

            </div>

            <!-- Si no hay resultados, se mostrara un en el pie del contenedor, que no se encontro ninugn resultado -->
            <div class="resultados_busqueda_pie_pagina_vacios">
    
            </div>
        </div>

        <nav>
            <a href="block_arena.php">
                <button class="boton_arena_bloques">Arena de bloques</button>
            </a>

            <?php
                // + Mensajes no leidos
                $mensajes = new Mensaje($con, $id_usuario_loggeado);
                $numero_mensajes = $mensajes->obtenerMensajesNoLeidos();

                // + Notificaciones no leidas
                $notificacion = new Notificacion($con, $id_usuario_loggeado);
                $numero_notificaciones = $notificacion->obtenerNotificacionesNoLeidas();

                // + Solicitudes de amistad
                $objeto_usuario = new Usuario($con, $id_usuario_loggeado);
                $numero_solicitudes_de_amistad = $objeto_usuario->obtenerNumeroDeSolicitudesDeAmistad();

                // + Invitaciones de grupo
                $numero_invitaciones_de_grupo = $objeto_usuario->obtenerNumeroDeInvitacionesGrupo();
            ?>


            <!-- Nombre del usuario para ir a la pagina del perfil del usuario -->
            <a href="<?php echo dirname($_SERVER['PHP_SELF']) ?>/<?php echo $usuario_loggeado ?>">
                <?php 
                echo $fila_detalles_usuario['nombre'];
                ?>
            </a>
            <a href="<?php echo dirname($_SERVER['PHP_SELF']) ?>/home.php">
                <i class="fa-solid fa-house-chimney"></i>
            </a>
            <!-- //! Esa parte es de la funcionalidad de dropdown (puede que la quite)-->
            <!-- Esto significa que ejecutaremos algo de javascript -->
            <a href="javascript:void(0);" onclick="obtenerInformacionDesplegable('<?php echo $id_usuario_loggeado ?>', 'mensaje')">
                <i class="fa-solid fa-envelope"></i>
                <?php
                if($numero_mensajes > 0)
                {
                    echo "<span class='insignia_notificacion' id='mensaje_no_leido'> " . $numero_mensajes . "</span>";
                }
                ?>
            </a>
            <a href="javascript:void(0);" onclick="obtenerInformacionDesplegable('<?php echo $id_usuario_loggeado ?>', 'notificacion')">
                <i class="fa-regular fa-bell"></i>
                <?php
                if($numero_notificaciones > 0)
                {
                    echo "<span class='insignia_notificacion' id='notificacion_no_leida'> " . $numero_notificaciones . "</span>";
                }
                ?>
            </a>
            <a href="<?php echo dirname($_SERVER['PHP_SELF']) ?>/requests.php">
                <i class="fa-solid fa-user-group"></i>
                <?php
                if($numero_solicitudes_de_amistad > 0)
                {
                    echo "<span class='insignia_notificacion' id='solicitud_de_amistad_no_leida'> " . $numero_solicitudes_de_amistad . "</span>";
                }
                ?>
            </a>
            
            <a href="<?php echo dirname($_SERVER['PHP_SELF']) ?>/groups.php">
                <i class="fa-solid fa-users"></i>
                <?php
                if($numero_invitaciones_de_grupo > 0)
                {
                    echo "<span class='insignia_notificacion' id='mensaje_no_leido'> " . $numero_invitaciones_de_grupo . "</span>";
                }
                ?>
            </a>

            <a href="<?php echo dirname($_SERVER['PHP_SELF']) ?>/settings.php">
                <i class="fa-solid fa-gear"></i>
            </a>
            <?php
            if ($tipo_usuario == "moderador" || $tipo_usuario == "administrador")
            {
            ?>
            <a href="javascript:void(0);" onclick="obtenerInformacionDesplegable('<?php echo $id_usuario_loggeado ?>', 'botones')">
                    <i class="fa-solid fa-hammer"></i>
                </a>
            <?php
            }
            else if ($tipo_usuario == "normal")
            {
            ?>
            <a href="<?php echo dirname($_SERVER['PHP_SELF']) ?>/help.php">
                <i class="fa-solid fa-circle-info"></i>
            </a>
            <?php
            }
            ?>
            <a href="<?php echo dirname($_SERVER['PHP_SELF']) ?>/includes/handlers/logout.php">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </nav>

        <div class="ventana_desplegable" style="height:0px;"> </div>
        <input type="hidden" id="ventana_despliegue_de_datos" value="">
     </div>

      <!-- Funcion para el scroll infinito en JQUERY -->
    <script>
            $(function() {
                // - Esta variable guarda el nombre del usuario loggeado
                var id_usuario_loggeado = '<?php echo $id_usuario_loggeado; ?>';
                // - Esta variable sera true si esta cargando publicaciones, si no las esta cargando, sera false
                var despliegueEnProgreso = false;

                // ! Explicar esta parte
                $(".ventana_desplegable").scroll(function()
                {
                    var elementoInferior = $(".ventana_desplegable a").last();
                    var noMasMensajes = $(".ventana_desplegable").find('.noMasInfoDropdown').val();

                    if(isElementInView(elementoInferior[0]) && noMasMensajes == 'false')
                    {
                        cargarMensajesDropdown();
                    }

                });

                // + Funcion de cargar posts
                function cargarMensajesDropdown() {
                    // + Si esta en progreso de cargar posts, retornar, esto para evitar errores
                    if (despliegueEnProgreso) {
                        return;
                    }

                    // + Como ya esta cargando posts, entonces nuestra variables sera true y se mostrara nuestro gif de cargando
                    despliegueEnProgreso = true;

                    // - Esta variable buscara el valor de la siguiente pagina a cargar, si no se encuentra siguiente pagina, entonces debe estar cargando, por lo que se le asignara 1
                    var pagina = $('.ventana_desplegable').find('.dropdownSiguientePagina').val() || 1;

                    // - Guardara el nombre de la pagina a la que mandaremos el ajax
                    var nombrePagina;
                    var tipo = $("#ventana_despliegue_de_datos").val();

                    if(tipo == 'notificacion')
                    {
                        nombrePagina = "ajax_load_notifications.php";
                    }
                    else if (tipo == 'mensaje')
                    {
                        nombrePagina = "ajax_load_messages.php";
                    }

                    // $ ajax -> Permite que un usuario de la aplicacion web interactue con una pagina web sin que se vuelva a cargar la pagina
                    $.ajax({
                        // + Este archivo creara una nueva publicacion y cargara las publicaciones
                        url: "<?php echo dirname($_SERVER['PHP_SELF']) ?>/includes/handlers/" + nombrePagina,
                        type: "POST",
                        // + Esto es lo que se manda a la pagina
                        // + ESTA ES LA REQUEST AL AJAX_LOAD_POSTS ES LO QUE TENDRA DENTRO REQUEST
                        data: "pagina=" + pagina + "&id_usuario_loggeado=" + id_usuario_loggeado,
                        cache: false,

                        // + Success -> Si la peticion ha sido satisfactoria, entonces ejecutara la siguiente funcion
                        success: function(response) {
                            // $ remove() -> Remueve el elemento
                            $('.ventana_desplegable').find('.dropdownSiguientePagina').remove(); // + Removemos el elemento .siguientePagina actual 
                            $('.ventana_desplegable').find('.noMasInfoDropdown').remove(); // + Removemos el elemento .noMasPublicaciones actual

                            // $ append() -> Inserta el contenido al final del elemento
                            // + En este caso, despues del div de area_publciaciones, se insertaran todos nuestros posts
                            $(".ventana_desplegable").append(response);

                            // + Como ya no esta cargando, asignamos a la variable en progreso falsa
                            despliegueEnProgreso = false;
                        }
                    });
                }

                // + Checar si el elemento esta a la vista
                function isElementInView(element) {
                    // $ getBoundingClientRect() -> Devuelve el tamaño de un elemento y su posicion relariva respecto a la ventana grafica
                    var rectangulo = element.getBoundingClientRect();

                    return (
                        // + Los bordes del rectangulo (top, left, bottom y right) cambian sus valores cada vez que la posicion scrolling cambia.
                        // + (Ya que sus valores no son absolutos sino relativos a la ventana)
                        // + Retornara
                        // ! falta explicar mejor esta parte
                        rectangulo.top >= 0 &&
                        rectangulo.left >= 0 &&
                        rectangulo.bottom <= (window.innerHeight || document.documentElement.clientHeight) && //* or $(window).height()
                        rectangulo.right <= (window.innerWidth || document.documentElement.clientWidth) //* or $(window).width()
                    );
                }
            });
        </script>

     <!-- Este div sera para el cuerpo principal de nuestros otros archivos -->
    <div class="cuerpo_principal">