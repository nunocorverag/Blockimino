<?php
include("../../config/config.php");

if(isset($_GET['id_usuario']) && isset($_GET['link_proyecto']))
{
    $subida_exitosa = 1;

    $id_usuario_loggeado = $_GET['id_usuario'];
    $link_proyecto = $_GET['link_proyecto'];

    $nombre_proyecto = $_POST['resultado'];

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

    if($subida_exitosa == 1)
    {

        $directorio_destino = "assets/projects/";
        $link_archivo = substr($link_proyecto, strpos($link_proyecto, "_") + 1);

        $nombre_archivo_proyecto = $directorio_destino . uniqid() . "_" . $link_archivo;

        copy("../../" . $link_proyecto, "../../" . $nombre_archivo_proyecto);
    }

    if($subida_exitosa == 1)
    {
        $query_copiar_proyecto = mysqli_query($con, "INSERT INTO proyectos VALUES ('', '$nombre_proyecto', '$id_usuario_loggeado', '$nombre_archivo_proyecto', '1')");

        $query_obtener_username_usuario = mysqli_query($con, "SELECT username FROM usuarios WHERE id_usuario='$id_usuario_loggeado'");
        $fila_username_usuario = mysqli_fetch_array($query_obtener_username_usuario);
        $usuario_loggeado = $fila_username_usuario['username'];

        echo "Proyecto copiado con éxito";
    }
    else
    {
        echo $mensaje_de_error;
    }
}

?>