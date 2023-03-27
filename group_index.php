<?php
include("includes/header.php");

if(isset($_POST['crear_grupo']))
{
    $subida_exitosa = 1;
    $imagen_grupo = $_FILES['imagenGrupo']['name'];
    $mensaje_de_error = "";

    if($imagen_grupo != "")
    {
        $directorio_destino = "assets/images/group_image_pics/";
        // $uniqid -> Genera un id unico por si dos personas suben el archivo con el mismo nombre
        // $basename -> Va a ser la extension de la imagen .jpg, .png
        $imagen_grupo = $directorio_destino . uniqid() . basename($imagen_grupo);
        $tipoArchivoImagen = pathinfo($imagen_grupo, PATHINFO_EXTENSION);

        // + Checamos el tamaño en bytes, el maximo sera 
        if($_FILES['imagenGrupo']['size'] > 10000000)
        {
            $mensaje_de_error = "Tu archivo es demasiado pesado, no se pudo crear el grupo!";
            $subida_exitosa = 0;
        }

        if(strtolower($tipoArchivoImagen) != "jpeg" && strtolower($tipoArchivoImagen) != "png" && strtolower($tipoArchivoImagen) != "jpg")
        {
            $mensaje_de_error = "Solo se permiten archivos de tipo: jpeg, jpg o png!";
            $subida_exitosa = 0;
        }

        if($subida_exitosa == 1)
        {
            echo "Directiorio:" . $_FILES['imagenGrupo']['tmp_name'];
            if(move_uploaded_file($_FILES['imagenGrupo']['tmp_name'], $imagen_grupo))
            {
                // + La imagen se subio con exito!
            }
            else
            {
                $subida_exitosa = 0;
            }
        }
    }

    if($subida_exitosa == 1)
    {
        // + Creamos un objeto nuevo tipo Publicacion con los parametros de conexion y el nombre del usuario loggeado
        $objeto_grupo = new Grupo($con, $id_usuario_loggeado);
        // + Llamamos el metodo dentro de la clase para publicar lo que este dentro de nuestra text area llamada "publicar_texto"
        $objeto_grupo->crearGrupo($_POST['nombre_grupo'], $_POST['desripcion_grupo'] , $imagen_grupo);
        // + Refrescamos la pagina para que no nos pida confirmar reenvio de formulario
        header("Location: group_index.php");
    }
    else
    {
        echo "<div class='alert alert-danger' style='text-align:center;'>
                $mensaje_de_error
            </div>";
    }
}
?>
<a href="create_group.php">Crear nuevo grupo</a>

<?php
$objeto_grupo = new Grupo($con, $id_usuario_loggeado);
$objeto_grupo->displayGrupos();

?>