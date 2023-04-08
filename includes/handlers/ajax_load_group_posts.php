<!-- Este archivo se encargara de establecer el limite de elementos por vez de carga (para que no se sature el usuario) -->
<!-- Tambien se encargara de mandar la informacion a la publicacion -->
<?php
include("../../config/config.php");
include("../classes/Usuario.php");
include("../classes/Publicacion.php");

//  - Numero de publicaciones para ser cargadas por llamada
$limite = 10;

// $ $_REQUEST -> Es una tabla asociativa que reagrupa los metodos $_GET, $_POST y $_COOKIE
// + $_REQUEST ES LO QUE SE MANDA CON DATA en el AJAX
// + Accedemos a las variables utilizando $_REQUEST
$publicaciones = new Publicacion($con, $_REQUEST['id_usuario_loggeado']);
$publicaciones->cargarPublicacionesGrupos($_REQUEST, $limite);
?>