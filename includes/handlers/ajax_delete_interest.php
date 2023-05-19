<?php
include("../../config/config.php");

if(isset($_GET['id_usuario']) && isset($_GET['id_interes']))
{
    $id_usuario_loggeado = $_GET['id_usuario'];
    $id_interes = $_GET['id_interes'];

    if(isset($_POST['resultado']))
    {
        if($_POST['resultado'] == true)
        {
            $query_eliminar_interes = mysqli_query($con, "DELETE FROM temas_interes WHERE id_interes='$id_interes' AND id_usuario_interesado='$id_usuario_loggeado'");
        }
    }
}

?>