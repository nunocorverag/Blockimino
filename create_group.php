<?php
include("includes/header.php");

// + Si el boton de publicar fue presionado entonces:
if (isset($_POST['crear_grupo'])) {
    $subida_exitosa = 1;
    $imagen_grupo = $_FILES['imagenGrupo']['name'];
    $mensaje_de_error = "";
    $nombre_grupo = $_POST['nombre_grupo'];
    $nombre_grupo = str_replace(' ', '_', $nombre_grupo);

    // + Validaciones de que el grupo no exista
    $checar_grupo_no_existe = mysqli_query($con, "SELECT * FROM grupos WHERE nombre_grupo='$nombre_grupo'");
    if(mysqli_num_rows($checar_grupo_no_existe) > 0)
    {
        $mensaje_de_error = "Error, el nombre del grupo elegido ya este en uso!";
        $subida_exitosa = 0;
    }

    if($imagen_grupo != "")
    {
        $directorio_destino = "assets/images/group_image_pics/";
        // $uniqid -> Genera un id unico por si dos personas suben el archivo con el mismo nombre
        // $basename -> Va a ser la extension de la imagen .jpg, .png
        $imagen_grupo = $directorio_destino . uniqid() . basename($imagen_grupo);
        $tipoArchivoImagen = pathinfo($imagen_grupo, PATHINFO_EXTENSION);

        // + Checamos el tamaño en bytes, el maximo sera 
        if($_FILES['imagenGrupo']['size'] > 10000000)
        {
            $mensaje_de_error = "Tu archivo es demasiado pesado, no se pudo crear el grupo!";
            $subida_exitosa = 0;
        }

        if(strtolower($tipoArchivoImagen) != "jpeg" && strtolower($tipoArchivoImagen) != "png" && strtolower($tipoArchivoImagen) != "jpg")
        {
            $mensaje_de_error = "Solo se permiten archivos de tipo: jpeg, jpg o png!";
            $subida_exitosa = 0;
        }

        if($subida_exitosa == 1)
        {
            echo "Directiorio:" . $_FILES['imagenGrupo']['tmp_name'];
            if(move_uploaded_file($_FILES['imagenGrupo']['tmp_name'], $imagen_grupo))
            {
                // + La imagen se subio con exito!
            }
            else
            {
                $mensaje_de_error = "La imagen no se subio de forma adecuada";
                $subida_exitosa = 0;
            }
        }
    }
    else
    {
        $mensaje_de_error = "El grupo debe tener una imagen!";
        $subida_exitosa = 0;  
    }

    if($subida_exitosa == 1)
    {
        // + Creamos un objeto nuevo tipo Publicacion con los parametros de conexion y el nombre del usuario loggeado
        $objeto_grupo = new Grupo($con, $id_usuario_loggeado);
        // + Llamamos el metodo dentro de la clase para publicar lo que este dentro de nuestra text area llamada "publicar_texto"
        $objeto_grupo->crearGrupo($nombre_grupo, $_POST['desripcion_grupo'] , $imagen_grupo);
        // + Refrescamos la pagina para que no nos pida confirmar reenvio de formulario
        header("Location: groups.php");
    }
    else
    {
        echo "<div class='alert alert-danger' style='text-align:center;'>
                $mensaje_de_error
            </div>";
    }

}
?>

<div class="contenedor_titulo_crear_grupo">
    <h4>Crear grupo</h4>
</div>
<div class="area_crear_grupo">
    <form class="formulario_crear_grupo" action="create_group.php" method="POST" enctype="multipart/form-data">
        <div class="nombre_grupo_container">
            <textarea name="nombre_grupo" id="nombre_grupo" placeholder="Nombre de grupo" required><?php echo isset($_POST['nombre_grupo']) ? $_POST['nombre_grupo'] : ''; ?></textarea>
            <div class="icono_imagen_container">
                <!-- //TODO FALTA PONER QUE EL USUARIO COMPLETE EL CAMPO SI NO SUBE IMAGEN DE GRUPO, O MAS BIEN, QUE PONGA UNA DEFAULT -->
                <input type="file" name="imagenGrupo" id="imagenGrupo" style="display:none">
                <i class="fa-regular fa-image" id="icono_imagen">
                    <span class="tooltip" id="tooltip"></span>
                </i>
                <span class="palomita">
                    <i class="fa-solid fa-check"></i>
                </span>
            </div>
        </div>
        <br>
        <div class="descripcion_grupo_container">
            <textarea name="desripcion_grupo" id="desripcion_grupo" placeholder="Descripción del grupo (opcional)"><?php echo isset($_POST['desripcion_grupo']) ? $_POST['desripcion_grupo'] : ''; ?></textarea>
        </div>
        <input type="submit" name="crear_grupo" id="boton_crear_grupo" value="Crear grupo">
    </form>
</div>

<script>
    // ! explicar este script
    const inputFile = document.getElementById("imagenGrupo");
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


</body>

</html>