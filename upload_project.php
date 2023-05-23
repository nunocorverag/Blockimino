<?php
include("includes/header.php");

if(isset($_POST['subir_proyecto']))
{
    $subida_exitosa = 1;
    $nombre_archivo_proyecto = $_FILES['archivo_proyecto']['name'];
    $nombre_proyecto = $_POST['crear_proyecto_titulo'];
    $nombre_proyecto = str_replace(' ', '_', $nombre_proyecto);
    $visibilidad_proyecto = isset($_POST['visibilidad_proyecto']) ? $_POST['visibilidad_proyecto'] : 0;
    $mensaje_de_error = "";

    if($nombre_archivo_proyecto != "")
    {
        $query_comprobar_nombre_proyecto = mysqli_query($con, "SELECT * FROM proyectos WHERE id_usuario_proyecto='$id_usuario_loggeado' AND nombre_proyecto='$nombre_proyecto'");
        
        $nombre_proyecto_bd = "";

        if(mysqli_num_rows($query_comprobar_nombre_proyecto) > 0)
        {
            $fila_comprobar_nombre_proyecto = mysqli_fetch_array($query_comprobar_nombre_proyecto);
            $nombre_proyecto_bd = $fila_comprobar_nombre_proyecto['nombre_proyecto'];
        }


        if($nombre_proyecto == $nombre_proyecto_bd)
        {
            $mensaje_de_error = "Error, ya tienes un proyecto con ese nombre!";
            $subida_exitosa = 0;
        }

        $directorio_destino = "assets/projects/";
        // $uniqid -> Genera un id unico por si dos personas suben el archivo con el mismo nombre
        // $basename -> Va a ser la extension de la imagen .jpg, .png
        $nombre_archivo_proyecto = $directorio_destino . uniqid() . "_" . basename($nombre_archivo_proyecto);
        $tipoArchivoProyecto = pathinfo($nombre_archivo_proyecto, PATHINFO_EXTENSION);

        // + Checamos el tamaño en bytes, el maximo sera 
        if($_FILES['archivo_proyecto']['size'] > 10000000)
        {
            $mensaje_de_error = "Tu archivo es demasiado pesado, no se pudo completar la publicación";
            $subida_exitosa = 0;
        }

        if(strtolower($tipoArchivoProyecto) != "blckmno")
        {
            $mensaje_de_error = "Solo se permiten archivos de tipo: blckmno!";
            $subida_exitosa = 0;
        }

        if($subida_exitosa == 1)
        {
            if(move_uploaded_file($_FILES['archivo_proyecto']['tmp_name'], $nombre_archivo_proyecto))
            {
                // + La imagen se subio con exito!
            }
            else
            {
                $subida_exitosa = 0;
            }
        }
    }
    else
    {
        $mensaje_de_error = "Debe de subir un archivo!";
        $subida_exitosa = 0;  
    }

    if($subida_exitosa == 1)
    {
        $query_crear_proyecto = mysqli_query($con, "INSERT INTO proyectos VALUES ('', '$nombre_proyecto', '$id_usuario_loggeado', '$nombre_archivo_proyecto', '$visibilidad_proyecto')");
        header("Location: " . $usuario_loggeado . "/projects");
    }
    else
    {
        echo "<div class='alert alert-danger' style='text-align:center;'>
                $mensaje_de_error
            </div>
            <br>";
    }


}
?>

<div class="contenedor_titulo_subir_proyecto">
    <h4>Subir proyecto</h4>
</div>
<div class="subir_proyecto">
    <form class="formulario_subir_proyecto" action="upload_project.php" method="POST" enctype="multipart/form-data">
        <div class="subir_proyecto_container_superior">
            <textarea name="crear_proyecto_titulo" id="crear_proyecto_titulo" placeholder="Nombre del proyecto" required><?php echo isset($_POST['crear_proyecto_titulo']) ? $_POST['crear_proyecto_titulo'] : ''; ?></textarea>
            <div class="icono_archivo_container">
                <input type="file" name="archivo_proyecto" id="archivo_proyecto" value="<?php echo isset($_POST['archivo_proyecto'])?>" style="display: none;">
                <i class="fa-solid fa-file" id="icono_archivo">
                    <span class="tooltip" id="tooltip"></span>
                </i>
                <span class="palomita">
                    <i class="fa-solid fa-check"></i>
                </span>
            </div>
        </div>
        <br>
        <div class="visibilidad_proyecto">
            <label>Visibilidad del proyecto:</label>
            <button id="publico-btn" class="visibilidad-btn selected" type="button">Público</button>
            <button id="privado-btn" class="visibilidad-btn" type="button">Privado</button>
            <input type="hidden" name="visibilidad_proyecto" id="visibilidad_proyecto" value="1">
        </div>
        <br>
        <input type="submit" name="subir_proyecto" class="boton_subir_proyecto" id="boton_subir_proyecto" value="Subir proyecto">
    </form>
</div>

<script>
    // ! explicar este script
    const inputFile = document.getElementById("archivo_proyecto");
    const iconoArchivo = document.getElementById("icono_archivo");
    const palomita = document.querySelector(".palomita");
    const tooltip = iconoArchivo.querySelector(".tooltip");

    iconoArchivo.onclick = function() {
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

<!-- //+ Cambio de color de los campos de visibilidad y el valor que envia al post -->
<script>
    const publicoBtn = document.getElementById("publico-btn");
    const privadoBtn = document.getElementById("privado-btn");
    const visibilidadProyecto = document.getElementById("visibilidad_proyecto");

    publicoBtn.onclick = function() {
        publicoBtn.classList.add("selected");
        privadoBtn.classList.remove("selected");
        visibilidadProyecto.value = "1";
    }

    privadoBtn.onclick = function() {
        privadoBtn.classList.add("selected");
        publicoBtn.classList.remove("selected");
        visibilidadProyecto.value = "0";
    }
</script>