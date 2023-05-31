<?php
include("../../config/config.php");

$response = array(); // Arreglo para almacenar la respuesta

if ($_POST['temaID'] != "" && isset($_POST['bloqueID'])) {
    $temaID = $_POST['temaID'];

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
    // Devolver la respuesta como JSON
    echo json_encode($response);
}
?>