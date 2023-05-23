<?php
include("includes/header.php");

if(isset($_POST['nuevo_proyecto']))
{
    $subida_exitosa = 1;
    $nombre_proyecto = $_POST['nuevo_proyecto_titulo'];$nombre_proyecto = $_POST['nuevo_proyecto_titulo'];
    $nombre_proyecto = str_replace(' ', '_', $nombre_proyecto);

    $visibilidad_proyecto = isset($_POST['visibilidad_proyecto']) ? $_POST['visibilidad_proyecto'] : 0;
    $mensaje_de_error = "";

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

    if($subida_exitosa)
    {
        $directorio_destino = "assets/projects/";
        $nombre_archivo_proyecto = $directorio_destino . uniqid() . "_" . $nombre_proyecto . ".blckmno";
    
        file_put_contents($nombre_archivo_proyecto, '');
    }


    if($subida_exitosa == 1)
    {
        $query_crear_proyecto = mysqli_query($con, "INSERT INTO proyectos VALUES ('', '$nombre_proyecto', '$id_usuario_loggeado', '$nombre_archivo_proyecto', '$visibilidad_proyecto')");
        header("Location: block_arena.php?project=$nombre_proyecto");
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

<div class="contenedor_titulo_crear_proyecto">
    <h4>Crear proyecto</h4>
</div>
<div class="crear_proyecto">
    <form class="formulario_crear_proyecto" action="new_project.php" method="POST" enctype="multipart/form-data">
        <textarea name="nuevo_proyecto_titulo" id="nuevo_proyecto_titulo" placeholder="Nombre del proyecto" required><?php echo isset($_POST['nuevo_proyecto_titulo']) ? $_POST['nuevo_proyecto_titulo'] : ''; ?></textarea>
        <br>
        <div class="visibilidad_proyecto">
            <label>Visibilidad del proyecto:</label>
            <button id="publico-btn" class="visibilidad-btn selected" type="button">Público</button>
            <button id="privado-btn" class="visibilidad-btn" type="button">Privado</button>
            <input type="hidden" name="visibilidad_proyecto" id="visibilidad_proyecto" value="1">
        </div>
        <br>
        <input type="submit" name="nuevo_proyecto" class="boton_crear_proyecto" id="boton_crear_proyecto" value="Crear proyecto">
    </form>
</div>

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