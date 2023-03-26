<?php
include("includes/header.php");

// + Si el boton de publicar fue presionado entonces:
if(isset($_POST['publicar']))
{
    $subida_exitosa = 1;
    $nombre_imagen = $_FILES['archivoASubir']['name'];
    $mensaje_de_error = "";

    if($nombre_imagen != "")
    {
        $directorio_destino = "assets/images/posts/";
        // $uniqid -> Genera un id unico por si dos personas suben el archivo con el mismo nombre
        // $basename -> Va a ser la extension de la imagen .jpg, .png
        $nombre_imagen = $directorio_destino . uniqid() . basename($nombre_imagen);
        $tipoArchivoImagen = pathinfo($nombre_imagen, PATHINFO_EXTENSION);

        // + Checamos el tamaño en bytes, el maximo sera 
        if($_FILES['archivoASubir']['size'] > 10000000)
        {
            $mensaje_de_error = "Tu archivo es demasiado pesado, no se pudo completar la publicación";
            $subida_exitosa = 0;
        }

        if(strtolower($tipoArchivoImagen) != "jpeg" && strtolower($tipoArchivoImagen) != "png" && strtolower($tipoArchivoImagen) != "jpg")
        {
            $mensaje_de_error = "Solo se permiten archivos de tipo: jpeg, jpg o png!";
            $subida_exitosa = 0;
        }

        if($subida_exitosa == 1)
        {
            echo "Directiorio:" . $_FILES['archivoASubir']['tmp_name'];
            if(move_uploaded_file($_FILES['archivoASubir']['tmp_name'], $nombre_imagen))
            {
                // + La imagen se subio con exito!
            }
            else
            {
                $subida_exitosa = 0;
            }
        }
        
    }

    if($subida_exitosa == 1)
    {
        // + Creamos un objeto nuevo tipo Publicacion con los parametros de conexion y el nombre del usuario loggeado
        $publicacion = new Publicacion($con, $id_usuario_loggeado);
        // + Llamamos el metodo dentro de la clase para publicar lo que este dentro de nuestra text area llamada "publicar_texto"
        $publicacion->enviarPublicacion($_POST['publicar_titulo'], $_POST['publicar_texto'], NULL, $nombre_imagen);
        // + Refrescamos la pagina para que no nos pida confirmar reenvio de formulario
        header("Location: home.php");
    }
    else
    {
        echo "<div class='alert alert-danger' style='text-align:center;'>
                $mensaje_de_error
            </div>";
    }
}
?>
<!-- //RF8 Habra una pantalla especifica para el foro -->
<!-- Como ya incluimos la clase header, que es la cabecera de nuestro archivo, no necesitamos escribir la cabecera -->
        <div class="contenedor_trends">
            <table class="tabla_trends">
                <tr>
                    <td colspan="3" class="titulo_trends">Trends</td>
                </tr>
                <tr>
                    <?php
                    $query_seleccionar_trends = mysqli_query($con, "SELECT * FROM trends ORDER BY hits DESC LIMIT 9");
                    $i = 0;
                    // + Sera el numero de top que abarca el trend
                    $top_num = 0;
                    foreach ($query_seleccionar_trends as $fila) {
                        $top_num++;
                        $palabra = $fila['trend'];
                        $palabra_puntos = strlen($palabra) >= 14 ? "..." : "";
                        $palabra_recortada = str_split($palabra, 14);
                        $palabra_recortada = $palabra_recortada[0];
                        if ($i % 3 == 0 && $i != 0) {
                            echo "</tr><tr>";
                        }
                        echo "<td>";
                        echo $top_num . ". " . $palabra_recortada . $palabra_puntos;
                        echo "</td>";
                        $i++;
                    }
                    ?>
                </tr>
            </table>
        </div>
        <br>
        <div class="columna_boton_publicar">
            <!-- Nos dirigira a crear nuestra publicacion -->
            <a href="post.php">
                <button>Crear publicacion</button>
            </a>
        </div>
        <br>
        <!-- Sera la columna principal que abarque la pagina -->
        <div class="columna_principal">
            <br>


            <!-- Sera el lugar donde estaran todas las publicaciones -->
            <div class="area_publicaciones"></div>
    
            <!-- Descargamos un gif de cargando para que se muestre cuando cargue las publicaciones -->
            <img id="cargando" src="assets/images/icons/cargando.gif">
        </div>
        <!-- Funcion para el scroll infinito en JQUERY -->
        <script>
                $(function() {
                    // - Esta variable guarda el nombre del usuario loggeado
                    var id_usuario_loggeado = '<?php echo $id_usuario_loggeado; ?>';
                    // - Esta variable sera true si esta cargando publicaciones, si no las esta cargando, sera false
                    var enProgreso = false;

                    cargarPosts();
                    // $ El simbolo de $ en jquery significa que voy a usar jquery, es un alias para JQuery
                    // $ window -> Representa la ventana abirta en el navegador
                    // $ scroll() -> El evento scroll ocurre cuando el usuario scrollea en el elemento especificado 
                    // + Partiendo de todas las definiciones, esta funcion se utilizara cuando el usuario se deslice por la ventana del navegador a traves de los posts
                    $(window).scroll(function() {
                        // $ .last() -> Devuelve el ultimo elemento de los elementos seleccionados
                        // + En este caso, devolvera la ultima publicacion en la vista actual
                        var elementoInferior = $(".publicacion").last();
                        // $ find() -> Busca a traves de los descendientes del div
                        // $ val() -> Devuelve el valor del elemento
                        // + Buscara dentro de area_publicaciones hasta encontrar noMasPublicaciones y encontrara su valor
                        // + Si es false, significa que hay mas publicaciones por cargar
                        // + Si es true, significa que ya no hay mas publicaciones por cargar
                        var noMasPublicaciones = $('.area_publicaciones').find('.noMasPublicaciones').val();

                        // isElementInViewport uses getBoundingClientRect(), which requires the HTML DOM object, not the jQuery object. The jQuery equivalent is using [0] as shown below.
                        // $ isElementInView() -> Detecta si el elemento esta visible
                        // + Verificamos si el elementoInferior[0] o sea, el elemento de hasta abajo (10 que es nuestro limite) durante la primera carga de posts, existe y todavia hay publicaciones por cargar
                        // + Entonces ejecutara el metodo de cargar posts
                        if (isElementInView(elementoInferior[0]) && noMasPublicaciones == 'false') {
                            cargarPosts();
                        }
                    });

                    // + Funcion de cargar posts
                    function cargarPosts() {
                        // + Si esta en progreso de cargar posts, retornar, esto para evitar errores
                        if (enProgreso) {
                            return;
                        }

                        // + Como ya esta cargando posts, entonces nuestra variables sera true y se mostrara nuestro gif de cargando
                        enProgreso = true;
                        // $ show -> Muestra un elemento escondido
                        $('#cargando').show();

                        // - Esta variable buscara el valor de la siguiente pagina a cargar, si no se encuentra siguiente pagina, entonces debe estar cargando, por lo que se le asignara 1
                        var pagina = $('.area_publicaciones').find('.siguientePagina').val() || 1;

                        // $ ajax -> Permite que un usuario de la aplicacion web interactue con una pagina web sin que se vuelva a cargar la pagina
                        $.ajax({
                            // + Este archivo creara una nueva publicacion y cargara las publicaciones
                            url: "includes/handlers/ajax_load_posts.php",
                            type: "POST",
                            // + Esto es lo que se manda a la pagina
                            // + ESTA ES LA REQUEST AL AJAX_LOAD_POSTS ES LO QUE TENDRA DENTRO REQUEST
                            data: "pagina=" + pagina + "&id_usuario_loggeado=" + id_usuario_loggeado,
                            cache: false,

                            // + Success -> Si la peticion ha sido satisfactoria, entonces ejecutara la siguiente funcion
                            success: function(response) {
                                // $ remove() -> Remueve el elemento
                                $('.area_publicaciones').find('.siguientePagina').remove(); // + Removemos el elemento .siguientePagina actual 
                                $('.area_publicaciones').find('.noMasPublicaciones').remove(); // + Removemos el elemento .noMasPublicaciones actual
                                $('.area_publicaciones').find('.noMasPublicacionesText').remove(); // + Removemos el elemento .noMasPublicacionesText actual 

                                // + Escondemos el gif de cargando porque ya ha cargado
                                $('#cargando').hide();
                                // $ append() -> Inserta el contenido al final del elemento
                                // + En este caso, despues del div de area_publciaciones, se insertaran todos nuestros posts
                                $(".area_publicaciones").append(response);

                                // + Como ya no esta cargando, asignamos a la variable en progreso falsa
                                enProgreso = false;
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

    </div>  <!-- Cierre de div cuerpo_principal -->
</body>
</html>