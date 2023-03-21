<?php
include("includes/header.php");

if(isset($_POST['cancelar']))
{
    header("Location: settings.php");
}
if(isset($_POST['cerrar_cuenta']))
{
    $query_cerrar_cuenta = mysqli_query($con, "UPDATE usuarios SET usuario_cerrado='si' WHERE id_usuario='$id_usuario_loggeado'");
    session_destroy();
    header("Location: index.php");
}
?>

<div class="columna_principal">
    <h4>Cerrar cuenta</h4>
    Estas seguro que quieres cerrar tu cuenta?
    <br><br>
    Cerrar tu cuenta va a esconder toda tu actividad de otros usuarios.
    <br>
    <br>
    Puedes reactivar tu cuenta volviendo a iniciar sesión
    <br>
    <br>
    <form action="closed_account.php" method="POST">
        <input type="submit" name="cerrar_cuenta" id="cerrar_cuenta" value="Confirmar" class="danger submit_settings">
        <input type="submit" name="cancelar" id="guardar_informacion" value="Cancelar" class="info submit_settings">
    </form>
</div>