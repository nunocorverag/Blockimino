<?php
    // Incluimos la cabecera para incluir todo lo que le programamos en css y html
    include("includes/header.php");

    // + Esto es recibido cuando un usuario hace click en el nombre de alguien, lo que hace que el htaccess mande a profile.php?perfil_usuario
    if (isset($_GET['perfil_usuario']))
    {
        // - Sera el nombre de usuario del perfil que visitemos
        $perfil_nombre_usuario = $_GET['perfil_usuario'];
        $query_informacion_usuario = mysqli_query($con, "SELECT * FROM usuarios WHERE username='$perfil_nombre_usuario'");

        if(mysqli_num_rows($query_informacion_usuario) > 0)
        {
            $arreglo_usuario = mysqli_fetch_array($query_informacion_usuario);
            $id_usuario_perfil = $arreglo_usuario['id_usuario'];

            $query_numero_proyectos_usuario = mysqli_query($con, "SELECT * FROM proyectos WHERE id_usuario_proyecto='$id_usuario_perfil'");
            $numero_proyectos = mysqli_num_rows($query_numero_proyectos_usuario);
            
            // $ substr_count -> Cuenta cuantas ocurrencias tiene un string dentro de otro string
            // + Identificamos a la cantidad de amigos con comas, le restamos una, porque tenemos en el arreglo de amigos una coma al principio
            $num_amigos = (substr_count($arreglo_usuario['lista_amigos'], ",")) - 1;
            $num_seguidos = (substr_count($arreglo_usuario['lista_seguidos'], ",")) - 1;
            $num_seguidores = (substr_count($arreglo_usuario['lista_seguidores'], ",")) - 1;
            $num_grupos = (substr_count($arreglo_usuario['lista_grupos'], ",")) - 1;
            $descripcion = $arreglo_usuario['descripcion'];
        }
        else
        {
            echo "Este perfil no existe o fue eliminado!";
        }
    }

    $query_seleccionar_proyectos_usuario = mysqli_query($con, "SELECT * FROM proyectos WHERE id_usuario_proyecto='$id_usuario_loggeado'");

    if(mysqli_num_rows($query_informacion_usuario) > 0)
    {

        if(isset($_POST['eliminar_amigo']))
        {
            $usuario = new Usuario($con, $id_usuario_loggeado);
            $usuario->eliminarAmigo($id_usuario_perfil);
            header("Location: " . $perfil_nombre_usuario);
        }

        if(isset($_POST['agregar_amigo']))
        {
            $usuario = new Usuario($con, $id_usuario_loggeado);
            $usuario->enviarSolicitudAmistad($id_usuario_perfil);
        }

        if(isset($_POST['responder_solicitud']))
        {
            header("Location: requests.php");
        }

        if(isset($_POST['dejar_seguir']))
        {
            $usuario = new Usuario($con, $id_usuario_loggeado);
            $usuario->dejarSeguir($id_usuario_perfil);
            header("Location: " . $perfil_nombre_usuario);
        }

        if(isset($_POST['seguir']))
        {
            $usuario = new Usuario($con, $id_usuario_loggeado);
            $usuario->seguirUsuario($id_usuario_perfil);
            header("Location: " . $perfil_nombre_usuario);
        }

        ?>
                <div class="contenedor_superior_perfil">
                    <div class="nombre_perfil">
                        <?php  
                        $objeto_usuario_perfil = new Usuario($con, $id_usuario_perfil);
                            echo "<h4> " . $objeto_usuario_perfil->obtenerNombreCompleto() . "</h4>";
                        ?>
                    </div>
                    <div class="detalles_usuario">
                        <div class="imagen_perfil">
                            <?php 
                            if($id_usuario_loggeado == $id_usuario_perfil)
                            {
                            ?>
                            <a href="upload.php">
                                <img src="<?php echo $arreglo_usuario['foto_perfil']; ?>" alt="">
                            </a>
                            <?php
                            }
                            else
                            {
                                ?>
                                <img src="<?php echo $arreglo_usuario['foto_perfil']; ?>" alt="">
                                <?php
                            }
                            ?>
                        </div>

                        <div class="informacion_perfil">
                            <div class="bloque bloque_1">
                                <a href="<?php echo $perfil_nombre_usuario ?>/friends?pagina=1">
                                    <p class="link_info"><?php echo "Amigos: " . $num_amigos ?></p>
                                </a>
                                <a href="<?php echo $perfil_nombre_usuario ?>/followed?pagina=1">
                                    <p class="link_info"><?php echo "Seguidos: " . $num_seguidos ?></p>
                                </a>
                                <p><?php echo "Publicaciones: " . $arreglo_usuario['num_posts']; ?></p>
                                <p><?php echo "Email: " . $arreglo_usuario['email']; ?></p>
                            </div>
                            <div class="bloque bloque_2">  

                                <a href="<?php echo $perfil_nombre_usuario ?>/followers?pagina=1">
                                    <p class="link_info"><?php echo "Seguidores: " . $num_seguidores ?></p>
                                </a>
                                <a href="<?php echo $perfil_nombre_usuario ?>/user_groups?pagina=1">
                                    <p class="link_info"><?php echo "Grupos: " . $num_grupos ?></p>
                                </a>
                                <a href="<?php echo $perfil_nombre_usuario ?>/projects">
                                    <p class="link_info"><?php echo "Proyectos: " . $numero_proyectos ?></p>
                                </a>
                                <p><?php echo "Likes: " . $arreglo_usuario['num_likes']; ?></p>
                            </div>
                            <div class="bloque bloque_3">
                            <?php $objeto_usuario_loggeado = new Usuario($con, $id_usuario_loggeado) ?>

                                <h1><?php echo $perfil_nombre_usuario ?></h1>
                                    <?php 
                                        if($id_usuario_loggeado != $id_usuario_perfil)
                                        {
                                            echo '<div class="info_perfil_inferior">';
                                            if ($objeto_usuario_loggeado->obtenerAmigosMutuos($id_usuario_perfil) == 0)
                                            {
                                                echo " No tienes amigos en común con este usuario! ";
                                            }
                                            else
                                            {
                                                $msg;
                                                if ($objeto_usuario_loggeado->obtenerAmigosMutuos($id_usuario_perfil) == 1)
                                                {
                                                    $msg = " Amigo";
                                                }
                                                else
                                                {
                                                    $msg = " Amigos";
                                                }
                                                    echo $objeto_usuario_loggeado->obtenerAmigosMutuos($id_usuario_perfil) . $msg . " en común";
                                            }
                                            echo '</div>';
                                        }
                                    ?>
                                <br>
                                <form action="<?php echo $perfil_nombre_usuario; ?>" method="POST">
                                    <?php 
                                        $objeto_perfil_usuario = new Usuario($con, $id_usuario_perfil);
                                        if($objeto_perfil_usuario->estaCerrado())
                                        {
                                            header("Location user_closed.php");
                                        }

                                        // + Este if comprobara si el usuario se encuentra en su perfil, o en el perfil de otro
                                        if($id_usuario_perfil != $id_usuario_loggeado)
                                        {
                                            if ($objeto_usuario_loggeado->esAmigo($id_usuario_perfil)) 
                                            {
                                                echo '<input type="submit" name="eliminar_amigo" class="danger" value="Eliminar Amigo"><br>';
                                            }
                                            else if ($objeto_usuario_loggeado->checarSolicitudRecibida($id_usuario_perfil))
                                            {
                                                echo '<input type="submit" name="responder_solicitud" class="warning" value="Responder Soliitud"><br>';
                                            }
                                            else if ($objeto_usuario_loggeado->checarSolicitudEnviada($id_usuario_perfil))
                                            {
                                                echo '<input type="submit" name="" class="default" value="Solicitud Enviada"><br>';
                                            }
                                            else
                                            {
                                                echo '<input type="submit" name="agregar_amigo" class="success" value="Agregar Amigo"><br>';
                                            }
                                            if(!($objeto_usuario_loggeado->esAmigo($id_usuario_perfil)))
                                            {
                                                if($objeto_usuario_loggeado->esSeguidor($id_usuario_perfil))
                                                {
                                                    echo '<br><input type="submit" name="dejar_seguir" class="danger" value="Dejar de seguir"><br>';
                                                }
                                                else
                                                {
                                                    echo '<br><input type="submit" name="seguir" class="success" value="Seguir"><br>';
                                                }
                                            }
                                        }

                                    ?>                    
                                </form>
                                <br>
                                <input type="submit" class="deep_blue" data-toggle="modal" data-target="#formulario_publicacion" value="Publicar algo">
                            </div>
                        </div>
                    </div>  <!-- Cierre div detalles_usuario -->
                    <div class="descripcion_perfil">
                        <p class="info_descripcion_perfil">
                            Descripción del perfil:
                            <?php echo $descripcion ?>
                        </p>
                    </div>

                </div>

                <div class="cuerpo_inferior">
                    <!-- Modal -->
                    <div class="modal fade" id="formulario_publicacion" tabindex="-1" role="dialog" aria-labelledby="modalLabelPublicacion">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Publicar algo</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="publicacion_perfil" id="publicacion_perfil" action="" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <div class="publicar_titulo_container">
                                                <div class="icono_archivo_container" style="margin: 0px 10px 0px 0px">
                                                    <input type="file" name="archivoASubir[]" id="archivoASubir" style="display:none" multiple>
                                                    <i class="fa-regular fa-file" id="icono_archivo">
                                                        <span class="tooltip tooltipFile" id="tooltipFile"></span>
                                                    </i>
                                                    <span class="palomitaFile">
                                                        <i class="fa-solid fa-check"></i>
                                                    </span>
                                                </div>
                                                <textarea name="publicar_titulo" id="publicar_titulo" placeholder="Titulo publicacion" required><?php echo isset($_POST['publicar_titulo']) ? $_POST['publicar_titulo'] : ''; ?></textarea>
                                                <div class="icono_imagen_container">
                                                    <input type="file" name="imagenASubir[]" id="imagenASubir" style="display:none" multiple>
                                                    <i class="fa-regular fa-image" id="icono_imagen">
                                                        <span class="tooltip tooltipImg" id="tooltipImg"></span>
                                                    </i>
                                                    <span class="palomitaImg">
                                                        <i class="fa-solid fa-check"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="publicar_texto_container">
                                                <textarea name="publicar_texto" id="publicar_texto" placeholder="Cuerpo de la publicacion" required><?php echo isset($_POST['publicar_texto']) ? $_POST['publicar_texto'] : ''; ?></textarea>
                                            </div>
                                            <div class="agregar_proyecto_container">
                                                <h4>Seleccionar proyecto (opcional)</h4>
                                                <select name="proyecto" id="proyecto">
                                                    <option value="">Seleccionar proyecto</option>
                                                    <?php
                                                    while ($fila_seleccionar_proyecto = mysqli_fetch_array($query_seleccionar_proyectos_usuario)) {
                                                        $nombre_proyecto = $fila_seleccionar_proyecto['nombre_proyecto'];
                                                        echo "<option value='$nombre_proyecto'>$nombre_proyecto</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <input type="hidden" name="publicado_por" value="<?php echo $id_usuario_loggeado?>">
                                            <input type="hidden" name="publicado_para" value="<?php echo $id_usuario_perfil?>">
                                        </div>
                                    </form>                                
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-primary" name="boton_publicar_perfil" id="enviar_publicacion_perfil">Publicar</button>
                                </div>
                            </div>
                        </div>
                        <script>
                            const inputImg = document.getElementById("imagenASubir");
                            const iconoImagen = document.getElementById("icono_imagen");
                            const palomitaImg = document.querySelector(".palomitaImg");
                            const tooltipImg = iconoImagen.querySelector(".tooltipImg");
                            var imageList = new DataTransfer();

                            iconoImagen.onclick = function() {
                                inputImg.click();
                            }

                            inputImg.onchange = function() {
                                var newImages = inputImg.files;
                                for (var i = 0; i < newImages.length; i++) {
                                    imageList.items.add(newImages[i]);
                                }
                                // Actualizar los archivos del inputImg con todas las imágenes
                                inputImg.files = imageList.files;
                                toolTipImg();
                            }

                            // Agregamos un event listener al documento
                            document.addEventListener("paste", function(e) {
                                if ($('#formulario_publicacion').is(':visible')) {
                                    // Verificar si el texto pegado es una imagen
                                    if (e.clipboardData && e.clipboardData.items) {
                                        var hasImage = false;
                                        for (var i = 0; i < e.clipboardData.items.length; i++) {
                                            var item = e.clipboardData.items[i];
                                            if (item.type.indexOf("image") !== -1) {
                                                hasImage = true;
                                                var file = item.getAsFile();
                                                imageList.items.add(file);
                                            }
                                        }
                                        if (hasImage) {
                                            inputImg.files = imageList.files;
                                            toolTipImg();
                                        }
                                    }
                                }
                            });

                            function toolTipImg() {
                                if (inputImg.files && inputImg.files.length > 0) {
                                    palomitaImg.classList.add("activo");
                                    if (inputImg.files.length === 1) {
                                        tooltipImg.innerHTML = inputImg.files.length + " archivo seleccionado:<br>";
                                    } else {
                                        tooltipImg.innerHTML = inputImg.files.length + " archivos seleccionados:<br>";
                                    }
                                    for (var i = 0; i < inputImg.files.length; i++) {
                                        tooltipImg.innerHTML += inputImg.files[i].name + " <button class='eliminar-imagen' data-index='" + i + "'>Eliminar</button><br>";
                                    }
                                    tooltipImg.style.visibility = "visible";
                                    tooltipImg.style.opacity = 1; // Set opacity to 1
                                } else {
                                    palomitaImg.classList.remove("activo");
                                    tooltipImg.style.visibility = "hidden";
                                    tooltipImg.style.opacity = 0; // Set opacity to 0
                                }
                            }

                            // + Eliminar la imagen
                            tooltipImg.addEventListener("click", function(e) {
                                e.stopPropagation(); // Evita que el evento se propague a los elementos superiores

                                if (e.target && e.target.classList.contains("eliminar-imagen")) {
                                    var index = e.target.getAttribute("data-index");
                                    if (index !== null) {
                                        index = parseInt(index);
                                        // Eliminar la imagen del arreglo de archivos
                                        imageList.items.remove(index);
                                        // Actualizar los archivos del inputImg con las imágenes restantes
                                        inputImg.files = imageList.files;
                                        // Actualizar el tooltip de imágenes
                                        toolTipImg();
                                    }
                                }
                            });

                            const inputFile = document.getElementById("archivoASubir");
                            const iconoFile = document.getElementById("icono_archivo");
                            const palomitaFile = document.querySelector(".palomitaFile");
                            const tooltipFile = iconoFile.querySelector(".tooltipFile");
                            var fileList = new DataTransfer();

                            iconoFile.onclick = function() {
                                inputFile.click();
                            }

                            inputFile.onchange = function() {
                                var newFiles = inputFile.files;
                                for (var i = 0; i < newFiles.length; i++) {
                                    fileList.items.add(newFiles[i]);
                                }
                                // Actualizar los archivos del inputImg con todas las imágenes
                                inputFile.files = fileList.files;
                                toolTipFile();
                            }

                            function toolTipFile() {
                                if (inputFile.files && inputFile.files.length > 0) {
                                    palomitaFile.classList.add("activo");
                                    if(inputFile.files.length == 1)
                                    {
                                        tooltipFile.innerHTML = inputFile.files.length + " archivo seleccionado:<br>";
                                    }
                                    else
                                    {
                                        tooltipFile.innerHTML = inputFile.files.length + " archivos seleccionados:<br>";
                                    }
                                    for (var i = 0; i < inputFile.files.length; i++) {
                                        tooltipFile.innerHTML += inputFile.files[i].name + " <button class='eliminar-archivo' data-index='" + i + "'>Eliminar</button><br>";
                                    }
                                    tooltipFile.style.visibility = "visible";
                                    tooltipFile.style.opacity = 1; // Set opacity to 1
                                } else {
                                    palomitaFile.classList.remove("activo");
                                    tooltipFile.style.visibility = "hidden";
                                    tooltipFile.style.opacity = 0; // Set opacity to 0
                                }
                            }

                            // + Eliminar el archivo
                            tooltipFile.addEventListener("click", function(e) {
                                e.stopPropagation(); // Evita que el evento se propague a los elementos superiores

                                if (e.target && e.target.classList.contains("eliminar-archivo")) {
                                    var index = e.target.getAttribute("data-index");
                                    if (index !== null) {
                                        index = parseInt(index);
                                        // Eliminar el archivo del arreglo de archivos
                                        fileList.items.remove(index);
                                        // Actualizar los archivos del inputFile con los archivos restantes
                                        inputFile.files = fileList.files;
                                        // Actualizar el tooltip de archivos
                                        toolTipFile();
                                    }
                                }
                            });
                        </script>
                    </div> <!-- //* Cierre del modal  -->

                    <div class="columna_principal_perfil">
                        <br>
                        
                        <!-- Sera el lugar donde estaran todas las publicaciones -->
                        <div class="area_publicaciones"></div>
            
                        <!-- Descargamos un gif de cargando para que se muestre cuando cargue las publicaciones -->
                        <img id="cargando" src="assets/images/icons/cargando.gif">
                    </div>
                </div>
                <!-- Funcion para el scroll infinito en JQUERY -->
                <script>
                    $(function() {
                        // - Esta variable guarda el nombre del usuario loggeado
                        var id_usuario_loggeado = '<?php echo $id_usuario_loggeado; ?>';
                        // - Sera el perfil en e que estamos
                        var id_usuario_perfil = '<?php echo $id_usuario_perfil; ?>';
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
                                url: "includes/handlers/ajax_load_profile_posts.php",
                                type: "POST",
                                // + Esto es lo que se manda a la pagina
                                // + ESTA ES LA REQUEST AL AJAX_LOAD_POSTS ES LO QUE TENDRA DENTRO REQUEST
                                data: "pagina=" + pagina + "&id_usuario_loggeado=" + id_usuario_loggeado + "&id_usuario_perfil=" + id_usuario_perfil,
                                cache: false,

                                // + Success -> Si la peticion ha sido satisfactoria, entonces ejecutara la siguiente funcion
                                success: function(response) {
                                    // $ remove() -> Remueve el elemento
                                    $('.area_publicaciones').find('.siguientePagina').remove(); // + Removemos el elemento .siguientePagina actual 
                                    $('.area_publicaciones').find('.noMasPublicaciones').remove(); // + Removemos el elemento .noMasPublicaciones actual

                                    // + Escondemos el gif de cargando porque ya ha cargado
                                    $('#cargando').hide();
                                    // $ append() -> Inserta el contenido al final del elemento
                                    // + En este caso, despues del div de area_publciaciones, se insertaran todos nuestros posts
                                    $(".area_publicaciones").append(response);

                                    // + Como ya no esta cargando, asignamos a la variable en progreso falsa
                                    enProgreso = false;
                                },
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
            <?php
        }
        ?>
    </body>
</html>