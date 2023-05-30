<?php
require '../../config/config.php';

if (isset($_GET['id_peticion_ayuda']))
{
    $id_peticion_ayuda = $_GET['id_peticion_ayuda'];

    if(isset($_POST['resultado']))
    {
        if($_POST['resultado'] == true)
        {
            $query_resolver_peticion = mysqli_query($con, "UPDATE peticiones_de_ayuda SET resuelto='si' WHERE id_peticion_ayuda='$id_peticion_ayuda'");
        }
    }
}
?>