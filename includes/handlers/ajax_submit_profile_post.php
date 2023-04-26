<?php
require '../../config/config.php';
include("../classes/Usuario.php");
include("../classes/Publicacion.php");
include("../classes/Notificacion.php");

if((isset($_POST['publicar_titulo'])) && isset($_POST['publicar_texto'])) {

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
            if(move_uploaded_file($_FILES['archivoASubir']['tmp_name'], "../../" . $nombre_imagen))
            {
                // + La imagen se subio con exito!
            }
            else
            {
                $mensaje_de_error = "Error: No se pudo subir al directorio";
                $subida_exitosa = 0;
            }
        }
        
    }

    if($subida_exitosa == 1)
    {
        // + Creamos un objeto nuevo tipo Publicacion con los parametros de conexion y el nombre del usuario loggeado
        $publicacion = new Publicacion($con, $_POST['publicado_por']);
        // + Llamamos el metodo dentro de la clase para publicar lo que este dentro de nuestra text area llamada "publicar_texto"
        $tipo_pagina = "pagina";
        $publicacion->enviarPublicacion($_POST['publicar_titulo'], $_POST['publicar_texto'], $_POST['publicado_para'], $nombre_imagen, $tipo_pagina, "");
        // + Refrescamos la pagina para que no nos pida confirmar reenvio de formulario
    }
    else
    {
        echo "<div class='alert alert-danger' style='text-align:center;'>
                $mensaje_de_error
            </div>";
    }
}