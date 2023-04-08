<?php
    include("../../config/config.php");

    $id_usuario_que_invito = $_POST['id_usuario_loggeado'];
    $id_usuario_invitado = $_POST['id_usuario_invitado'];
    $id_grupo = $_POST['id_grupo'];

    $query_enviar_invitacion = mysqli_query($con, "INSERT INTO invitaciones_de_grupo VALUES ('', '$id_usuario_que_invito', '$id_usuario_invitado', '$id_grupo')");
?>