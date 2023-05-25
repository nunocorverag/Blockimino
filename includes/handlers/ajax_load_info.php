<?php
include("../../config/config.php");

$response = array(); // Arreglo para almacenar la respuesta

if ($_POST['temaID'] != "") {
    $temaID = $_POST['temaID'];
    if(isset($_POST['bloqueID'])) {
        if($_POST['bloqueID'] != -1) {
            $seleccionar_bloque = true;
        } else {
            $seleccionar_bloque = false;
        }
    } else {
        $seleccionar_bloque = false;
    }

    if($seleccionar_bloque) {
        $bloqueID = $_POST['bloqueID'];
        $query_seleccionar_info_bloque = mysqli_query($con, "SELECT * FROM bloques_pagina WHERE id_tema_bloque='$temaID' AND id_bloque='$bloqueID'");
        if (mysqli_num_rows($query_seleccionar_info_bloque) > 0) {
            $fila = mysqli_fetch_array($query_seleccionar_info_bloque);
            $descripcion_bloque = $fila['descripcion_bloque'];
            $imagen_bloque = $fila['imagen_bloque'];
            $apendices_bloque = $fila['apendices_bloque'];
        
            // Interpretar saltos de línea en la descripción del tema
            $descripcion_bloque = nl2br($descripcion_bloque);

            // Agregar la información al arreglo de respuesta
            $response['descripcion'] = $descripcion_bloque;
            $response['imagen'] = $imagen_bloque;
            $response['apendices'] = $apendices_bloque;
        }
    } else {
        $query_seleccionar_info_tema = mysqli_query($con, "SELECT * FROM temas_pagina WHERE id_tema='$temaID'");
        if (mysqli_num_rows($query_seleccionar_info_tema) > 0) {
            $fila = mysqli_fetch_array($query_seleccionar_info_tema);
            $descripcion_tema = $fila['descripcion_tema'];
            $imagen_tema = $fila['imagen_tema'];
            $apendices_tema = $fila['apendices_tema'];

            // Interpretar saltos de línea en la descripción del tema
            $descripcion_tema = nl2br($descripcion_tema);

            // Agregar la información al arreglo de respuesta
            $response['descripcion'] = $descripcion_tema;
            $response['imagen'] = $imagen_tema;
            $response['apendices'] = $apendices_tema;
        }   
    }
}

// Devolver la respuesta como JSON
echo json_encode($response);
?>