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
        $tipo_pagina = "pagina";
        // $hashtags = $_POST['hashtags'];
        $hashtags = "";
        if(isset($_POST['hashtags'])){
            $hashtags = $_POST['hashtags'];
        }
        else
        {
            echo "Nada";
        }


        $publicacion->enviarPublicacion($_POST['publicar_titulo'], $_POST['publicar_texto'], NULL, $nombre_imagen, $tipo_pagina, $hashtags);

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

</div>


<div class="publicar_area">
    <form class="formulario_publicacion" action="post.php" method="POST" enctype="multipart/form-data">
        <div class="publicar_titulo_container">
            <textarea name="publicar_titulo" id="publicar_titulo" placeholder="Titulo publicacion" required><?php echo isset($_POST['publicar_titulo']) ? $_POST['publicar_titulo'] : ''; ?></textarea>
            <div class="icono_imagen_container">
                <input type="file" name="archivoASubir" id="archivoASubir" style="display:none">
                <i class="fa-regular fa-image" id="icono_imagen">
                    <span class="tooltip" id="tooltip"></span>
                </i>
                <span class="palomita">
                    <i class="fa-solid fa-check"></i>
                </span>
            </div>
        </div>
        <br>
        <div class="publicar_texto_container">
            <textarea name="publicar_texto" id="publicar_texto" placeholder="Cuerpo publicacion" required><?php echo isset($_POST['publicar_texto']) ? $_POST['publicar_texto'] : ''; ?></textarea>
        </div>
        <div class="agregar_hashtags_container">
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
    // ! explicar este script
    const inputFile = document.getElementById("archivoASubir");
    const iconoImagen = document.getElementById("icono_imagen");
    const palomita = document.querySelector(".palomita");
    const tooltip = iconoImagen.querySelector(".tooltip");

    iconoImagen.onclick = function() {
        inputFile.click();
    }

    inputFile.onchange = function() {
        if (inputFile.files && inputFile.files[0]) {
            palomita.classList.add("activo");
            tooltip.innerHTML = inputFile.files[0].name;
            tooltip.style.visibility = "visible";
            tooltip.style.opacity = 1; // Set opacity to 1
        } else {
            palomita.classList.remove("activo");
            tooltip.style.visibility = "hidden";
            tooltip.style.opacity = 0; // Set opacity to 0
        }
    }
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