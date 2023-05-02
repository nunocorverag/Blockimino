<!-- Este archivo pertenece a la conexion a la base de datos -->
<?php
ob_start(); // $ Activa el almacenamiento en buffer de la salida
/* 
 +$ Esto guardara la informacion php cuando la pagina cargue y pasara todo el
 +$ codigo php al navegador a la vez al final del archivo
 +$ Se utiliza para que la informacion ya se encuentre disponible a la hora
 +$ de que el cliente acceda a nuestra pagina
*/

session_start(); // $ Inicia una nueva sesion o reanuda la sesion existente
/* 
  +$ Esto nos servira para guardar informacion en una variable superglobal y de esta
  +$ manera poder acceder a datos en nuestra base de datos con esa variable
*/



// + Guardamos en una variable la fecha y hora actual de nuestra ubicacion
$timezone = date_default_timezone_set("America/Mexico_City");

/*
  + Guardaremos en diferentes variables los parametros que necesitamos para conectarnos
  + a nuestra base de datos, esto sirve para que sea sencillo si necesitamos cambiar una variable
  + y no tener que hacerlo directamente en la conexion a la misma
*/
$host = "localhost";    //El host de nuestra base de datos
$user = "root";         //El nombre de usuario de nuestra base de datos
$password = "";         //La contraseña de nuestra base de datos
$database = "blockimino";   //El nombre de nuestra base de datos

// $  mysqli_connect -> Abre una nueva conexion al servidor MySQL
// +  Guardaremos en una variable la conexion a nuestra base de datos
$con = mysqli_connect($host, $user, $password, $database);
  
// $  mysqli_connect_errno -> Nos arroja el error en codigo del ultimo intento de conexion a la base de datos

// +  Revisamos si existen errores, si existen, entonces mostraremos el error
if(mysqli_connect_errno())
{
    // $ echo -> Imprime la cadena que le indiquemos
    // + Aqui imprimimos que hubo un fallo al conectar a la base de datos e indicamos cual fue
    echo "Fallo al conectar: " . mysqli_connect_errno();
}
?>
