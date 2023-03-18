
<?php
require '../../config/config.php';
include("../classes/Usuario.php");
include("../classes/Publicacion.php");
include("../classes/Notificacion.php");

if((isset($_POST['titulo_publicacion'])) && isset($_POST['cuerpo_publicacion'])) {
    $publicacion = new Publicacion($con, $_POST['publicado_por']);
    $publicacion->enviarPublicacion($_POST['titulo_publicacion'], $_POST['cuerpo_publicacion'], $_POST['publicado_para']);
}
?>
