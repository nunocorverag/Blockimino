<?php
class Grupo {
    // - $con -> Guardara la conexion de la base de datos
    private $con;
    // - $n_usuario -> Guardara un objeto tipo usuario, el cual pertenecera a la clase Usuario
    private $objeto_usuario;

    public function __construct($con, $id_usuario)
    {
        $this->con = $con;
        // + Guardara en el objeto usuario, la conexion a la base de datos y el nombre de usuario
        $this->objeto_usuario = new Usuario($con, $id_usuario);
    }

    public function crearGrupo($nombre_grupo, $descripcion_grupo, $imagen_grupo)
    {
        //TODO FALTA EL MENSAJE DE ERROR DE QUE EL NOMBRE DEL GRUPO YA EXISTE
        // + Validaciones de que el grupo no exista
        $checar_grupo_no_existe = mysqli_query($this->con, "SELECT * FROM grupos WHERE nombre_grupo='$nombre_grupo'");
        if(mysqli_num_rows($checar_grupo_no_existe) > 0)
        {
            echo "Error El grupo ya existe!";
            return;
        }
        $id_creador_grupo = $this->objeto_usuario->obtenerIDUsuario();
        $crear_grupo = mysqli_query($this->con, "INSERT INTO grupos VALUES ('', '$nombre_grupo', '$id_creador_grupo', '$imagen_grupo', '$descripcion_grupo', ',$id_creador_grupo,')");
        $id_regresado = mysqli_insert_id($this->con);
        $agregar_grupo_al_usuario = mysqli_query($this->con, "UPDATE usuarios SET lista_grupos = CONCAT(lista_grupos, '$id_regresado,') WHERE id_usuario = '$id_creador_grupo'");
        echo "Grupo creado con éxito!";
    }

    public function displayGrupos()
    {
        $id_usuario = $this->objeto_usuario->obtenerIDUsuario();
        $grupos_usuario = $this->objeto_usuario->obtenerListaGrupos();
        $grupos_usuario_explode = explode(",", $grupos_usuario);
        $grupos_usuario_explode = array_filter($grupos_usuario_explode);

        foreach($grupos_usuario_explode as $grupo)
        {
            echo "Grupos: " . $grupo . "<br>"; 
        }
    }
}




?>