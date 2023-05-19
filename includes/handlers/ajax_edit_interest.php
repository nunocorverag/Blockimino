<?php
include("../../config/config.php");

if(isset($_GET['id_usuario']) && isset($_GET['id_interes']))
{
    $id_usuario_loggeado = $_GET['id_usuario'];
    $id_interes = $_GET['id_interes'];

    $cantidad_interes = $_POST['resultado'];

    $query_actualizar_interes = mysqli_query($con, "UPDATE temas_interes SET cantidad_interes='$cantidad_interes' WHERE id_interes='$id_interes' AND id_usuario_interesado='$id_usuario_loggeado'");
}

?>