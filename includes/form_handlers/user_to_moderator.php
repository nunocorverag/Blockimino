<?php
require '../../config/config.php';

if (isset($_GET['id_usuario']))
{
    $id_usuario = $_GET['id_usuario'];

    if(isset($_POST['resultado']))
    {
        if($_POST['resultado'] == true)
        {    
            $query_cambiar_usuario_a_moderador =  mysqli_query($con, "UPDATE usuarios SET tipo='moderador' WHERE id_usuario='$id_usuario'");
        }
    }
}
?>