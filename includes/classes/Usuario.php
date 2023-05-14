<!-- Esta clase trabajara con cosas que se requiera hacer con los datos del usuario -->
<?php
class Usuario {
    // - $con -> Guardara la conexion de la base de datos
    private $con;
    // - $n_usuario -> Guardara los datos del usuario
    private $usuario;

    // $ __construct -> Es el contructor de la clase, es llamado cuando se crea un objecto de la clase Usuario
    // + De parametros pasamos:
    // + $con -> Que sera la conexion a la base de datos 
    // + $n_usuario -> Que sera el nombre de usuario
    // ! esta modificacion puede afectar a todos los objetos usuario y sus objetos, ya que cambiare que el parametro dea usuario por id
    public function __construct($con, $id_usuario)
    {
        // - this->con -> Es la variable con de esta clase, que esta declarada al principio
        $this->con = $con;
        // + Guardamos en una variable la query para seleccionar los datos del usuario que se nos proporciono a la hora de crear el objeto
        $query_detalles_usuario = mysqli_query($con, "SELECT * FROM usuarios WHERE id_usuario='$id_usuario'");
        // - this->usuario -> Es la variable datos_usuario de esta clase que declaramos al principio
        //  + Guardamos en un arreglo los datos del usuario que nos proporcionaron en el objeto
        $this->usuario = mysqli_fetch_array($query_detalles_usuario);
    }

    // + Esta funcion obtendra el nombre completo del usuario
    public function obtenerNombreCompleto()
    {
        // + Como ya tenemos una variable con todos los datos del usuario, simplemente nos hace falta regresarlos en un string combinado
        return $this->usuario['nombre'] . " " . $this->usuario['apeP'] . " " . $this->usuario['apeM'];
    }

    public function obtenerNumeroDeSolicitudesDeAmistad()
    {
        $id_usuario = $this->obtenerIDUsuario();
        $query_obtener_numero_solicitudes_amistad = mysqli_query($this->con, "SELECT * FROM solicitudes_de_amistad WHERE usuario_solicitado='$id_usuario'");
        return mysqli_num_rows($query_obtener_numero_solicitudes_amistad);
    }

    public function obtenerNumeroDeInvitacionesGrupo()
    {
        $id_usuario = $this->obtenerIDUsuario();
        $query_obtener_numero_invitaiones_grupos = mysqli_query($this->con, "SELECT * FROM invitaciones_de_grupo WHERE id_usuario_invitado='$id_usuario'");
        return mysqli_num_rows($query_obtener_numero_invitaiones_grupos);
    }

    // + Esta funcion regresara el numero de publicaciones del usuario
    public function obenerNumeroPublicaciones()
    {
        return $this->usuario['num_posts'];
    }

    //  + Regresa el id del usuario
    public function obtenerIDUsuario()
    {
        return $this->usuario['id_usuario'];
    }

    //  + Regresa la foto de perfil del usuario
    public function obtenerFotoPerfil()
    {
        return $this->usuario['foto_perfil'];
    }

    public function obtenerTipoUsuario()
    {
        return $this->usuario['tipo'];
    }

    //  + Regresa la lista de amigos del usuario
    public function obtenerListaAmigos() {
        return $this->usuario['lista_amigos'];
    }

    //  + Regresa la lista de seguidos por el usuario
    public function obtenerListaSeguidos() {
        
        return $this->usuario['lista_seguidos'];
    }
    //  + Regresa la lista de seguidores del usuario
    public function obtenerListaSeguidores() {
        return $this->usuario['lista_seguidores'];
    }

    public function obtenerListaGrupos() {
        return $this->usuario['lista_grupos'];
    }

    // + Regresa true si el usuario esta cerrado y false si el usuario no esta cerrado
    public function estaCerrado()
    {
        if($this->usuario['usuario_cerrado'] == 'si')
        {
            return true;
        }
        else 
        {
            return false;
        }
    }

    // + Regresa el nombre de usuario
    public function obtenerNombreUsuario()
    {
        return $this->usuario['username'];
    }

    // + Verifica si el usuario es amigo de otro usuario
    public function esAmigo($id_usuario_a_verificar)
    {
        // + Verifica si el nombre de usuario esta dentro del arreglo, si es asi, entonces si es amigo
        $usuario_a_checar = "," . $id_usuario_a_verificar . ",";
        // $ strstr() -> Devuelve la primera ocurrencia dentro de un string
        // + Si el usuario esta dentro del arreglo de amigos o el nombre de usuario es el mismo usuario
        if ((strstr($this->usuario['lista_amigos'], $usuario_a_checar)) || $id_usuario_a_verificar == $this->usuario['id_usuario'])
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //+ Verfiica si el usuario al que se sigue esta dentro del arreglo
    public function esSeguidor($id_usuario_a_verificar)
    {
        $usuario_a_checar = "," . $id_usuario_a_verificar . ",";
        if ((strstr($this->usuario['lista_seguidos'], $usuario_a_checar)) || $id_usuario_a_verificar == $this->usuario['id_usuario'])
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    // + Checa si se envio la solicitud
    public function checarSolicitudRecibida($usuario_que_solicito)
    {
        // - Aqui se guardara el usuario que hizo la solicitud de amistad
        $usuario_solicitado = $this->usuario['id_usuario'];
        $query_checar_solicitud = mysqli_query($this->con, "SELECT * FROM solicitudes_de_amistad WHERE usuario_solicitado='$usuario_solicitado' AND usuario_que_solicito='$usuario_que_solicito'");
        if((mysqli_num_rows($query_checar_solicitud)) > 0)
        {
            return true;
        }
        else 
        {
            return false;
        }
    }

    // + Checa si se recibio la solicitud
    public function checarSolicitudEnviada($usuario_solicitado)
    {
        // - Aqui se guardara el usuario que hizo la solicitud de amistad
        $usuario_que_solicito = $this->usuario['id_usuario'];
        $query_checar_solicitud = mysqli_query($this->con, "SELECT * FROM solicitudes_de_amistad WHERE usuario_solicitado='$usuario_solicitado' AND usuario_que_solicito='$usuario_que_solicito'");
        if((mysqli_num_rows($query_checar_solicitud)) > 0)
        {
            return true;
        }
        else 
        {
            return false;
        }
    }

    public function eliminarAmigo($usuario_a_remover)
    {
        $usuario_loggeado = $this->usuario['id_usuario'];
        // + Obtenemos la lista de amigos de el usuario que queremos remover
        $query_obtener_lista_amigos = mysqli_query($this->con, "SELECT id_usuario, lista_amigos FROM usuarios WHERE id_usuario='$usuario_a_remover'");
        $fila = mysqli_fetch_array($query_obtener_lista_amigos);
        // + Obtenemos la lista de amigos del usuario que queremos eliminar de amigo
        $usuario_lista_amigos = $fila['lista_amigos'];
        $id_usuario_a_remover = $fila['id_usuario'];

        // + Removemos al amigo del usuario loggeado
        $nueva_lista_de_amigos_usuario_loggeado = str_replace("," . $id_usuario_a_remover . ",", ",", $this->usuario['lista_amigos']);
        $query_eliminar_amigo = mysqli_query($this->con, "UPDATE usuarios SET lista_amigos='$nueva_lista_de_amigos_usuario_loggeado' WHERE id_usuario='$usuario_loggeado'");

        // + Removemos al usuario loggeado del usuario que este quiso remover
        $nueva_lista_de_amigos_usuario_removido = str_replace("," . $this->usuario['id_usuario'] . ",", ",", $usuario_lista_amigos);
        $query_eliminar_amigo = mysqli_query($this->con, "UPDATE usuarios SET lista_amigos='$nueva_lista_de_amigos_usuario_removido' WHERE id_usuario='$usuario_a_remover'");
    }

    public function dejarSeguir($usuario_a_remover)
    {
        $usuario_loggeado = $this->usuario['id_usuario'];
        // + Obtener la lista de seguidores de el usuario que queremos dejar de seguir
        $query_obtener_arreglo_seguidores = mysqli_query($this->con, "SELECT id_usuario, lista_seguidores FROM usuarios WHERE id_usuario='$usuario_a_remover'");
        $fila = mysqli_fetch_array($query_obtener_arreglo_seguidores);
        // + Obtenemos la lista de seguidores del usuario que queremos remover de seguido
        $usuario_lista_seguidores = $fila['lista_seguidores'];
        $id_usuario_a_remover = $fila['id_usuario'];

        // + Removemos al seguido
        $nueva_lista_seguidos_usuario_loggeado = str_replace("," . $id_usuario_a_remover . ",", ",", $this->usuario['lista_seguidos']);
        $query_eliminar_seguido = mysqli_query($this->con, "UPDATE usuarios SET lista_seguidos='$nueva_lista_seguidos_usuario_loggeado' WHERE id_usuario='$usuario_loggeado'");

        // + Removemos al seguidor
        $nueva_lista_seguidores_usuario_removido = str_replace("," . $this->usuario['id_usuario'] . ",", ",", $usuario_lista_seguidores);
        $query_eliminar_seguidor = mysqli_query($this->con, "UPDATE usuarios SET lista_seguidores='$nueva_lista_seguidores_usuario_removido' WHERE id_usuario='$usuario_a_remover'");
    }

    public function enviarSolicitudAmistad($usuario_solicitado){
        $usuario_que_solicito = $this->usuario['id_usuario'];
        $query_agregar_solicitud = mysqli_query($this->con, "INSERT INTO solicitudes_de_amistad VALUES('', '$usuario_solicitado', '$usuario_que_solicito')");
    }

    public function seguirUsuario($usuario_solicitado)
    {
        $id_seguidor = $this->usuario['id_usuario'];

        // + Query para obtener el id_usuario, el nombre de usuario y la lista de seguidos del usuario que se solicito seguir
        $query_seleccionar_seguido = mysqli_query($this->con, "SELECT id_usuario FROM usuarios WHERE id_usuario='$usuario_solicitado'");
        $fila = mysqli_fetch_array($query_seleccionar_seguido);

        $id_seguido = $fila['id_usuario'];

        $query_agregar_seguido = mysqli_query($this->con, "UPDATE usuarios SET lista_seguidos=CONCAT(lista_seguidos, '$id_seguido,') WHERE id_usuario='$id_seguidor'");
        $query_agregar_seguidor = mysqli_query($this->con, "UPDATE usuarios SET lista_seguidores=CONCAT(lista_seguidores, '$id_seguidor,') WHERE id_usuario='$id_seguido'");

        $lista_seguidores_seguidor = $this->usuario['lista_seguidores'];

        $query_seleccionar_seguido = mysqli_query($this->con, "SELECT  lista_seguidos, lista_seguidores FROM usuarios WHERE id_usuario='$usuario_solicitado'");
        $fila = mysqli_fetch_array($query_seleccionar_seguido);
        $lista_seguidos_seguido = $fila['lista_seguidos'];
        $lista_seguidores_seguido = $fila['lista_seguidores'];
        
        $lista_seguidores_seguidor_explode = explode(",", $lista_seguidores_seguidor);
        $ambos_se_siguen = false;
        foreach($lista_seguidores_seguidor_explode as $seguidor_del_seguidor)
        {
            if($seguidor_del_seguidor == $id_seguido)
            {
                $ambos_se_siguen = true;
            }
        }

        if($ambos_se_siguen == true)
        {         
            // + Removemos al seguido
            $nueva_lista_seguidos_usuario_loggeado = str_replace($id_seguido . ",", "", $this->usuario['lista_seguidos']);
            $query_eliminar_seguido_usuario_loggeado = mysqli_query($this->con, "UPDATE usuarios SET lista_seguidos='$nueva_lista_seguidos_usuario_loggeado' WHERE id_usuario='$id_seguidor'");

            $nueva_lista_seguidores_usuario_loggeado = str_replace($id_seguido . ",", "", $this->usuario['lista_seguidores']);
            $query_eliminar_seguidor_usuario_loggeado = mysqli_query($this->con, "UPDATE usuarios SET lista_seguidores='$nueva_lista_seguidores_usuario_loggeado' WHERE id_usuario='$id_seguidor'");
    
            // + Removemos al seguidor
            $nueva_lista_seguidos_usuario_seguido = str_replace($this->usuario['id_usuario'] . ",", "", $lista_seguidos_seguido);
            $query_eliminar_seguido_usuario_seguido = mysqli_query($this->con, "UPDATE usuarios SET lista_seguidos='$nueva_lista_seguidos_usuario_seguido' WHERE id_usuario='$id_seguido'");

            $nueva_lista_seguidores_usuario_seguido = str_replace($this->usuario['id_usuario'] . ",", "", $lista_seguidores_seguido);
            $query_eliminar_seguidor_usuario_seguido = mysqli_query($this->con, "UPDATE usuarios SET lista_seguidores='$nueva_lista_seguidores_usuario_seguido' WHERE id_usuario='$id_seguido'");

            $query_agregar_amigo = mysqli_query($this->con, "UPDATE usuarios SET lista_amigos=CONCAT(lista_amigos, '$id_seguidor,') WHERE id_usuario='$id_seguido'");
            $query_agregar_amigo = mysqli_query($this->con, "UPDATE usuarios SET lista_amigos=CONCAT(lista_amigos, '$id_seguido,') WHERE id_usuario='$id_seguidor'");

            $query_checar_solicitudes_seguidor = mysqli_query($this->con, "SELECT * FROM solicitudes_de_amistad WHERE usuario_solicitado='$id_seguidor' AND usuario_que_solicito='$id_seguido'");
            $query_checar_solicitudes_seguido = mysqli_query($this->con, "SELECT * FROM solicitudes_de_amistad WHERE usuario_solicitado='$id_seguido' AND usuario_que_solicito='$id_seguidor'");

            $check_seguidor = mysqli_num_rows($query_checar_solicitudes_seguidor);
            $check_seguido = mysqli_num_rows($query_checar_solicitudes_seguido);
            if ($check_seguidor == 1 || $check_seguidor == 1)
            {
                $query_eliminar_solicitud = mysqli_query($this->con, "DELETE FROM solicitudes_de_amistad WHERE usuario_solicitado='$id_seguidor' AND usuario_que_solicito='$id_seguido'");
                $query_eliminar_solicitud = mysqli_query($this->con, "DELETE FROM solicitudes_de_amistad WHERE usuario_solicitado='$id_seguido' AND usuario_que_solicito='$id_seguidor'");
            }
        }
    }

    public function obtenerAmigosMutuos($usuario_a_checar) {
        $amigos_mutuos = 0;
        // + Arreglo de usuarios del usuario loggeado
        $lista_usuarios = $this->usuario['lista_amigos'];
        // $ explode -> Separa una string en un arreglo despues de un caracter dado
        // + Por lo tanto separara cada amigo despues de la coma y nos regresara un arreglo lleno de usuarios
        $lista_amigos_explode = explode(",", $lista_usuarios);

        // + Arreglo de usuarios del usuario a checar
        $query_obtener_arreglo_amigos_usuario_checar = mysqli_query($this->con, "SELECT lista_amigos FROM usuarios WHERE id_usuario='$usuario_a_checar'");
        $fila = mysqli_fetch_array($query_obtener_arreglo_amigos_usuario_checar);
        $lista_usuarios_de_usuario_a_checar = $fila['lista_amigos'];
        $lista_usuarios_de_usuario_a_checar_explode = explode(",", $lista_usuarios_de_usuario_a_checar);

        // + El primer foreach iterara a traves de la lista de amigos del primer usuario
        foreach($lista_amigos_explode as $i)
        {
            // + El segundo foreach iterara a traves de la lista de amigos del usuario que queremos checar cuantos amigos tenemos en comun
            // + Checara por cada elemento del arreglo del usuario loggeado, todos los amigos para ver cuantos coiciden a la hora de compararlos
            foreach($lista_usuarios_de_usuario_a_checar_explode as $j)
            {
                //+ Si coinciden y el arreglo no esta vacio, entonces aumentara el numero de amigos mutuos
                if($i == $j && $i != "")
                {
                    $amigos_mutuos ++;
                }
            }
        }
        return $amigos_mutuos;
    }
}

?>