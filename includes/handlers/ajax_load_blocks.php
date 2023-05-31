<?php
include("../../config/config.php");

if ($_POST['temaID'] != "") {
    $temaID = $_POST['temaID'];
    $query_seleccionar_bloques_tema = mysqli_query($con, "SELECT * FROM bloques_pagina WHERE id_tema_bloque='$temaID' ORDER BY id_bloque ASC");

    if (mysqli_num_rows($query_seleccionar_bloques_tema) > 0) {
        $options = ""; // Inicializar opciones vacías
    
        $first_row = mysqli_fetch_array($query_seleccionar_bloques_tema); // Obtener el primer elemento del arreglo
        $nombre_bloque = $first_row['nombre_bloque'];
        $id_bloque = $first_row['id_bloque'];
        $options .= "<option value='$id_bloque' selected>$nombre_bloque</option>"; // Opción predeterminada
    
        while ($fila_bloques = mysqli_fetch_array($query_seleccionar_bloques_tema)) {
            $nombre_bloque = $fila_bloques['nombre_bloque'];
            $id_bloque = $fila_bloques['id_bloque'];
            $options .= "<option value='$id_bloque'>$nombre_bloque</option>";
        }
        echo $options;
    }
}
?>