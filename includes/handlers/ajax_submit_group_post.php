<?php
require '../../config/config.php';
include("../classes/Usuario.php");
include("../classes/Publicacion.php");
include("../classes/Notificacion.php");

if((isset($_POST['publicar_titulo'])) && isset($_POST['publicar_texto'])) {
    $subida_exitosa = 1;
    // + Verificar proyecto
    $id_usuario_loggeado = $_POST['publicado_por'];
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
        for($i = 0; $i < count($_FILES['imagenASubir']['name']); $i++) {
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
            if(!(move_uploaded_file($_FILES['imagenASubir']['tmp_name'][$i], "../../" . $imagen)))
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
    
            if(!(move_uploaded_file($_FILES['archivoASubir']['tmp_name'][$j], "../../" . $archivo)))
            {
                $mensaje_de_error = "Error: No se pudo subir un archivo al directorio";
                $subida_exitosa = 0;
                break;
            }
        }
        $j++;
    }

    if($subida_exitosa == 1)
    {
        // + Creamos un objeto nuevo tipo Publicacion con los parametros de conexion y el nombre del usuario loggeado
        $publicacion = new Publicacion($con, $_POST['publicado_por']);
        // + Llamamos el metodo dentro de la clase para publicar lo que este dentro de nuestra text area llamada "publicar_texto"
        $tipo_pagina = "grupo";
        $publicacion->enviarPublicacion($_POST['publicar_titulo'], $_POST['publicar_texto'], NULL, $nombres_imagenes, $nombres_archivos, $id_proyecto, $_POST['id_grupo'], "");
        // + Refrescamos la pagina para que no nos pida confirmar reenvio de formulario
    }
    else
    {
        header('Content-Type: application/json');
        $response = array(
            'status' => 'error'
        );
        // $ json_encode() -> Toma una variable en php y la convierte en una cadena de JSON
        json_encode($response);

        echo $mensaje_de_error;
    }
}