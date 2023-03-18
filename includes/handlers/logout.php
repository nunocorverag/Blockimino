<?php
// + Utilizamos session_start para hacerle saber al programa que vamos a trabajar con sesiones
session_start();
// $ session_destroy -> Destruye toda la informacion registrada de una sesion
session_destroy();
// + Regirigimos a index.php porque el usuario ya no tiene una sesion activa
header("Location: ../../index.php");
?>