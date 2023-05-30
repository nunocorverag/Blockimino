<?php
require '../../config/config.php';

if (isset($_GET['id_sancion']))
{
    $id_sancion = $_GET['id_sancion'];

    if(isset($_POST['resultado']))
    {
        if($_POST['resultado'] == true)
        {    
            $query_verificar_sancion = mysqli_query($con, "SELECT * FROM sanciones WHERE id_sancion='$id_sancion'");
            $fila = mysqli_fetch_array($query_verificar_sancion);
            $razon_sancion = $fila['razon_sancion'];
            if($razon_sancion == 'Demasiados intentos fallidos de inicio de sesión (5)' || $razon_sancion == 'Intentos fallidos de inicio de sesión presistentes (10)' || $razon_sancion == '"Exceso de intentos fallidos de sesión (15)')
            {
                $usuario_sancionado = $fila['id_usuario_sancionado'];
                $query_resetear_intentos = mysqli_query($con, "UPDATE usuarios SET intentos_inicio_sesion=0 WHERE id_usuario='$usuario_sancionado'");
            }
            $query_eliminar_sancion = mysqli_query($con, "UPDATE sanciones SET sancion_eliminada='si' WHERE id_sancion='$id_sancion'");

        }
    }
}
?>