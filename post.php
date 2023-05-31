<?php
include("includes/header.php");

// + Si el boton de publicar fue presionado entonces:
if(isset($_POST['publicar']))
{
    $subida_exitosa = 1;

    // + Verificar proyecto
    $id_proyecto = "";
    if(isset($_POST['proyecto']))
    {
        $nombre_proyecto = $_POST['proyecto'];
        if($nombre_proyecto != "")
        {
            $query_seleccionar_id_proyecto = mysqli_query($con, "SELECT id_proyecto FROM proyectos WHERE nombre_proyecto='$nombre_proyecto' AND id_usuario_proyecto='$id_usuario_loggeado'");
            if(mysqli_num_rows($query_seleccionar_id_proyecto) > 0)
            {
                $fila_id_proyecto = mysqli_fetch_array($query_seleccionar_id_proyecto);
                $id_proyecto = $fila_id_proyecto['id_proyecto'];
    
            }
            else
            {
                $mensaje_de_error = "El proyecto no existe! Puede ser que se haya eliminado antes de realizar la publicación!";
                $subida_exitosa = 0;
            }
        }        
    }

    // + imagenes
    if(count($_FILES['imagenASubir']['name']) > 5)
    {
        $mensaje_de_error = "Solo se puede subir un maximo de 5 imagenes";
        $subida_exitosa = 0;
    }

    if($_FILES['imagenASubir']['name'][0] != "")
    {
        $nombres_imagenes = "|";
        $hay_imagenes = true;
    }
    else
    {
        $nombres_imagenes = "";
        $hay_imagenes = false;
    }

    if($subida_exitosa && $hay_imagenes)
    {
        for($i = 0; $i < count($_FILES['imagenASubir']['name']); $i++) 
        {
            $nombre_imagen = $_FILES['imagenASubir']['name'][$i];
            $mensaje_de_error = "";
        
            if($nombre_imagen != "")
            {
                $directorio_destino = "assets/posts/images/";
                // $uniqid -> Genera un id unico por si dos personas suben el archivo con el mismo nombre
                // $basename -> Va a ser la extension de la imagen .jpg, .png
                $nombre_imagen = $directorio_destino . uniqid() . "_" . basename($nombre_imagen);
                $tipoArchivoImagen = pathinfo($nombre_imagen, PATHINFO_EXTENSION);
        
                if($subida_exitosa == 1)
                {
                    // + Checamos el tamaño en bytes, el maximo sera 
                    if($_FILES['imagenASubir']['size'][$i] > 10000000)
                    {
                        $mensaje_de_error = "Una imagen es demasiado pesada, no se pudo completar la publicación";
                        $subida_exitosa = 0;
                        break;
                    }
            
                    if(strtolower($tipoArchivoImagen) != "jpeg" && strtolower($tipoArchivoImagen) != "png" && strtolower($tipoArchivoImagen) != "jpg")
                    {
                        $mensaje_de_error = "Se detecto en las imagenes un archivo inválido, solo se permiten imagenes de tipo: jpeg, jpg o png!";
                        $subida_exitosa = 0;
                        break;
                    }
                    $nombres_imagenes .= $nombre_imagen . "|";
                }
            }
        }
    }

    // + archivos
    if(count($_FILES['archivoASubir']['name']) > 5)
    {
        $mensaje_de_error = "Solo se puede subir un maximo de 5 archivos";
        $subida_exitosa = 0;
    }

    if($_FILES['archivoASubir']['name'][0] != "")
    {
        $nombres_archivos = "|";
        $hay_archivos = true;
    }
    else
    {
        $nombres_archivos = "";
        $hay_archivos = false;
    }

    if($subida_exitosa && $hay_archivos)
    {
        for($i = 0; $i < count($_FILES['archivoASubir']['name']); $i++) 
        {
            $nombre_archivo = $_FILES['archivoASubir']['name'][$i];
            $mensaje_de_error = "";
        
            if($nombre_archivo != "")
            {
                $directorio_destino = "assets/posts/files/";
                // $uniqid -> Genera un id unico por si dos personas suben el archivo con el mismo nombre
                // $basename -> Va a ser la extension de la imagen .jpg, .png
                $nombre_archivo = $directorio_destino . uniqid() . "_" . basename($nombre_archivo);
                $tipoArchivoArchivo = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
        
                if($subida_exitosa == 1)
                {
                    // + Checamos el tamaño en bytes, el maximo sera 
                    if($_FILES['archivoASubir']['size'][$i] > 10000000)
                    {
                        $mensaje_de_error = "Un archivo es demasiado pesado, no se pudo completar la publicación";
                        $subida_exitosa = 0;
                        break;
                    }
            
                    if(strtolower($tipoArchivoArchivo) != "txt" && strtolower($tipoArchivoArchivo) != "blckmno" && strtolower($tipoArchivoArchivo) != "ino")
                    {
                        $mensaje_de_error = "Se detecto en los archivos un archivo inválido, solo se permiten archivos de tipo: txt, blckmno o ino!";
                        $subida_exitosa = 0;
                        break;
                    }
                    $nombres_archivos .= $nombre_archivo . "|";
                }
            }
        }
    }

    // + Insertar imagenes y archivos en el directorio

    $lista_imagenes_explode = explode("|", $nombres_imagenes);
    $lista_imagenes_explode = array_filter($lista_imagenes_explode);

    $i = 0;
    foreach($lista_imagenes_explode as $imagen)
    {
        if($subida_exitosa == 1 && $hay_imagenes)
        {
            if(!(move_uploaded_file($_FILES['imagenASubir']['tmp_name'][$i], $imagen)))
            {
                $mensaje_de_error = "Error: No se pudo subir una imagen al directorio";
                $subida_exitosa = 0;
                break;
            }
        }
        $i++;
    }

    $lista_archivos_explode = explode("|", $nombres_archivos);
    $lista_archivos_explode = array_filter($lista_archivos_explode);

    $j = 0;

    foreach($lista_archivos_explode as $archivo)
    {
        if($subida_exitosa == 1 && $hay_archivos)
        {
    
            if(!(move_uploaded_file($_FILES['archivoASubir']['tmp_name'][$j], $archivo)))
            {
                $mensaje_de_error = "Error: No se pudo subir un archivo al directorio";
                $subida_exitosa = 0;
                break;
            }
            $j++;
        }
    }


    if($subida_exitosa == 1)
    {
        // + Creamos un objeto nuevo tipo Publicacion con los parametros de conexion y el nombre del usuario loggeado
        $publicacion = new Publicacion($con, $id_usuario_loggeado);
        // + Llamamos el metodo dentro de la clase para publicar lo que este dentro de nuestra text area llamada "publicar_texto"
        $tipo_pagina = "pagina";
        $hashtags = "";
        if(isset($_POST['hashtags'])){
            $hashtags = $_POST['hashtags'];
        }

        $publicacion->enviarPublicacion($_POST['publicar_titulo'], $_POST['publicar_texto'], NULL, $nombres_imagenes, $nombres_archivos, $id_proyecto, $tipo_pagina, $hashtags);

        // + Refrescamos la pagina para que no nos pida confirmar reenvio de formulario
        header("Location: home.php");
    }
    else {
        echo "<div class='alert alert-danger' style='text-align:center;'>
                $mensaje_de_error
              </div>";
    }
}

$query_seleccionar_proyectos_usuario = mysqli_query($con, "SELECT * FROM proyectos WHERE id_usuario_proyecto='$id_usuario_loggeado'");
?>


<div class="contenedor_publicar_algo">
    <h4>Publicar algo</h4>
</div>
<div class="publicar_area">
    <form class="formulario_publicacion" action="post.php" method="POST" enctype="multipart/form-data">
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
            <textarea name="publicar_texto" id="publicar_texto" placeholder="Cuerpo publicacion" required><?php echo isset($_POST['publicar_texto']) ? $_POST['publicar_texto'] : ''; ?></textarea>
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
        <br>
        <div class="agregar_hashtags_container">
        <h4>Agregar hashtags (opcional)</h4>
            <input type="text" class="buscar_hashtag" id="buscar_hashtag" placeholder="Buscar hashtag">
            <button type="button" class="agregar_hashtag" id="agregar_hashtag">Agregar</button>
            <div class="hashtag_resultados_container" id="hashtag_resultados_container"></div>
            <input type="hidden" name="hashtags" id="hashtags" value="">
        </div>
        <br>
        <div class="contenedor_hashtags_agregados" id="contenedor_hashtags_agregados"></div>
        <br>
        <input type="submit" name="publicar" id="boton_publicar" value="Publicar">
    </form>
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


<!-- //+ En este script se buscaran los hashtags con los que coincida -->
<script>
    $(document).ready(function() {
        $('#buscar_hashtag').on('input', function() {
            var query = $(this).val();
            $.ajax({
                url: 'includes/handlers/ajax_search_hashtags.php',
                type: 'POST',
                data: {query:query},
                success: function(data) {
                    $('#hashtag_resultados_container').html(data);
                    $('.displayResultadoHashtag').click(function(){
                        var nombre_usuario = $(this).find('.hashtag_encontrado').text().trim();
                        $('#buscar_hashtag').val(nombre_usuario);
                        $('#hashtag_resultados_container').empty();
                    });
                }
            });
        });
    });
</script>
                       
<script>
    // + Script de agregar hashtags
    // ! Explicar este script
    $(document).ready(function(){
        const agregarHashtag = document.getElementById("agregar_hashtag");
        const buscarHashtag = document.getElementById("buscar_hashtag");
        const contenedorHashtags = document.getElementById("contenedor_hashtags_agregados");
        const hashtagsAgregados = new Set(); // Set para almacenar los hashtags agregados
        const inputHashtags = document.getElementById("hashtags");

        // Función para actualizar el input de los hashtags agregados
        function actualizarInputHashtags() {
            let hashtags = "";
            hashtagsAgregados.forEach(function(hashtag) {
                hashtags += hashtag + ",";
            });
            inputHashtags.value = hashtags.slice(0, -1); // Eliminar la última coma
        }

        agregarHashtag.onclick = function() {
            let hashtag = buscarHashtag.value.trim();
            if (hashtag !== "") {
                $('#hashtag_resultados_container').empty(); //Cerrar el Live Search
                // + Si el hashtag no comienza con "#", agregarlo
                if (!hashtag.startsWith("#")) {
                    hashtag = "#" + hashtag;
                }

                // + Convertir todas las letras a minusculas
                hashtag = hashtag.toLowerCase();

                // + Reemplazar los espacios por guiones bajos
                hashtag = hashtag.replace(/\s+/g, "_");

                // + Verificar si el hashtag (sin el símbolo "#") ya existe
                if (hashtagsAgregados.has(hashtag.toLowerCase())) { // Comparar en minúsculas
                    buscarHashtag.value = "";
                    return;
                }

                // + Crear el div del hashtag
                const divHashtag = document.createElement("div");
                divHashtag.className = "hashtag_agregado";
                divHashtag.innerHTML = hashtag;

                // + Crear el botón para eliminar el hashtag
                const botonEliminar = document.createElement("button");
                botonEliminar.className = "boton_eliminar_hashtag btn btn-danger";
                botonEliminar.innerHTML = "x";
                botonEliminar.onclick = function() {
                    divHashtag.remove();
                    hashtagsAgregados.delete(hashtag.toLowerCase()); // Eliminar en minúsculas
                    actualizarInputHashtags();
                };
                divHashtag.appendChild(botonEliminar);

                // + Agregar el div del hashtag al contenedor
                contenedorHashtags.appendChild(divHashtag);

                // + Agregar el hashtag al set
                hashtagsAgregados.add(hashtag.toLowerCase()); // Agregar en minúsculas
                actualizarInputHashtags();

                // + Limpiar el input
                buscarHashtag.value = "";
            }
        };
    });
</script>



</body>
</html>