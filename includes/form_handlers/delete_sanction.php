<?php
require '../../config/config.php';

if (isset($_GET['id_sancion']))
{
    $id_sancion = $_GET['id_sancion'];

    if(isset($_POST['resultado']))
    {
        if($_POST['resultado'] == true)
        {    
            $query_eliminar_sancion = mysqli_query($con, "DELETE FROM sanciones WHERE id_sancion='$id_sancion'");
        }
    }
}
?>