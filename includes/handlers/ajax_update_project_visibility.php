<?php
require '../../config/config.php';
if(isset($_POST['id_proyecto']))
{
    $id_proyecto = $_POST['id_proyecto'];
    $visibilidad = $_POST['visibilidad'];

    $query_actualizar_informacion = mysqli_query($con, "UPDATE proyectos SET visibilidad=" . $visibilidad . " WHERE id_proyecto='" . $id_proyecto . "'");
}
?>