<!-- Esta clase trabajara con todo lo que tenga que ver con mensajes -->
<?php
class Mensaje {
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

    public function obtenerUsuarioMasReciente()
    {
        $id_usuario_loggeado = $this->objeto_usuario->obtenerIDUsuario();
        // + Esto va a regresar un resultado de la tabla de mensajes donde el usuario loggeado la persona para la que fue el mensaje o la persona de quien fue el mensaje
        $query_regresa_detalles_ultimo_mensaje = mysqli_query($this->con, "SELECT mensaje_para, mensaje_de FROM mensajes WHERE mensaje_para='$id_usuario_loggeado' OR mensaje_de='$id_usuario_loggeado' ORDER BY id_mensaje DESC LIMIT 1");

        if(mysqli_num_rows($query_regresa_detalles_ultimo_mensaje) == 0)
        {
            return false;
        }
        $fila = mysqli_fetch_array($query_regresa_detalles_ultimo_mensaje);
        $mensaje_para = $fila['mensaje_para'];
        $mensaje_de = $fila['mensaje_de'];


        //! Falta entender mejor esta parte, a la funcion en general
        // + Si el usuario que encontramos no es igual al usuario loggeado
        if($mensaje_para != $id_usuario_loggeado)
        {
            // + Lo regresamos
            return $mensaje_para;
        }
        else
        // + En el caso en que sea igual, significa que el otro usuario es la otra persona del mensaje, entonces lo retornamos
        {
            return $mensaje_de;
        }
    }

    public function enviarMensaje($mensaje_para, $cuerpo_mensaje, $fecha_mensaje)
    {
        if($cuerpo_mensaje != "")
        {
            $id_usuario_loggeado = $this->objeto_usuario->obtenerIDUsuario();
            $query = mysqli_query($this->con, "INSERT INTO mensajes VALUES ('', '$mensaje_para', '$id_usuario_loggeado', '$cuerpo_mensaje', '$fecha_mensaje', 'no', 'no', 'no')");
        }
    }

    public function obtenerMensajes($otroUsuario)
    {
        $id_usuario_loggeado = $this->objeto_usuario->obtenerIDUsuario();
        $info = "";
        $query_actualizar_mensaje_visto = mysqli_query($this->con, "UPDATE mensajes SET abierto='si' WHERE mensaje_para='$id_usuario_loggeado' AND mensaje_de='$otroUsuario'");

        $query_obtener_mensajes = mysqli_query($this->con, "SELECT * FROM mensajes WHERE (mensaje_para='$id_usuario_loggeado' AND mensaje_de='$otroUsuario') OR (mensaje_de='$id_usuario_loggeado' AND mensaje_para='$otroUsuario')");

        while($fila = mysqli_fetch_array(($query_obtener_mensajes)))
        {
            $mensaje_para = $fila['mensaje_para'];
            $mensaje_de = $fila['mensaje_de'];
            $cuerpo_mensaje = $fila['cuerpo_mensaje'];

            // $ Este es un "conditional statement"
            // + Lo que hace es que guardamos una variable y ponemos una variable que vamos a evaluar
            // + Si lo que esta dentro es true (?), entonces la variable sera igual a lo que esta despues de "?", sino, sera igual a lo que esta despues de ":"
            $div_top = ($mensaje_para == $id_usuario_loggeado) ? "<div class='mensaje' id='verde'>" : "<div class='mensaje' id='azul'>";
            // + Info ira guardando cada vez mas mensajes, los mensajes que sean para el usuario (o sea los recibidos, seran de la clase green) y los mensajes sean para otro usuario (o sea los enviados, seran de la clase blue)
            $info = $info . $div_top . $cuerpo_mensaje . "</div><br><br>";
        }
        return $info;
    }

    public function obtenerUltimoMensaje($id_usuario_loggeado, $mensaje_para)
    {
        $arreglo_detalles = array();
        $query_obtener_mensajes = mysqli_query($this->con, "SELECT cuerpo_mensaje, mensaje_para, fecha_mensaje FROM mensajes WHERE (mensaje_para='$id_usuario_loggeado' AND mensaje_de='$mensaje_para') OR (mensaje_para='$mensaje_para' AND mensaje_de='$id_usuario_loggeado') ORDER BY id_mensaje DESC LIMIT 1");
        $fila = mysqli_fetch_array($query_obtener_mensajes);
        $mensaje_de = ($fila['mensaje_para'] == $id_usuario_loggeado) ? "Dijo: " : "Dijiste: ";
        $fecha_mensaje = $fila['fecha_mensaje'];

        #region Periodo de tiempo de los posts
        // - Guardamos la hora y fecha actuales
        $tiempo_actual = date("Y-m-d H:i:s");
        // - Guardamos la hora y fecha actuales en el que se realizo la publicacion
        $fecha_comienzo = new DateTime($fecha_mensaje);
        // - Guardamos la hora y fecha actuales
        $fecha_final = new DateTime($tiempo_actual);
        // - Realizamos una diferencia de tiempos de la fecha inicial, con la actual para saber cuanto tiempo lleva la publicacion publicada
        $intervalo = $fecha_comienzo->diff($fecha_final);
        // + Si el intervalo es 1 o mas años
        if($intervalo->y >= 1)
        {
            //Un año de antiguedad
            if($intervalo->y == 1)
            {
                $mensaje_tiempo = $intervalo->y . " año atrás";
            }
            //Más de un año de antiguedad
            else
            {
                $mensaje_tiempo = $intervalo->y . " años atrás";
            }
        }
        // + Si el intervalo es 1 o mas de 1 mes atras, pero menos de un año
        else if($intervalo->m >= 1)
        {
            // + Checamos los dias 
            // 0 dias
            if($intervalo->d == 0)
            {
                $dias = " atrás";
            }
            // 1 dia
            else if($intervalo->d == 1)
            {
                $dias = $intervalo->d. "día atrás";
            }
            //Mas de 1 dia
            else 
            {
                $dias = $intervalo->d . " días atrás";
            }

            //1 mes
            if($intervalo-> m == 1)
            {
                $mensaje_tiempo = $intervalo->m . " mes" . $dias;
            }
            //Mas de 1 mes
            else
            {
                $mensaje_tiempo = $intervalo->m . " meses" . $dias;
            }
        }
        // + Si el intervalo es 1 o mas dias atras, pero menos que un mes
        else if($intervalo->d >= 1)
        {
            //1 dia
            if($intervalo->d == 1)
            {
                $mensaje_tiempo = "ayer";
            }
            //Mas de un dia
            else 
            {
                $mensaje_tiempo = $intervalo->d . " días atrás";
            }
        }
        // + Si el intervalo es 1 o mas horas atras, pero menos que un dia
        else if($intervalo->h >= 1)
        {
            //1 hora atras
            if($intervalo->h == 1)
            {
                $mensaje_tiempo = $intervalo->h . " hora atrás";
            }
            //Mas de una hora
            else 
            {
                $mensaje_tiempo = $intervalo->h . " horas atrás";
            }
        }
        // + Si el intervalo es de 1 minuto o mas atras, pero menos que una hora
        else if($intervalo->i >= 1)
        {
            //1 minuto atras
            if($intervalo->i == 1)
            {
                $mensaje_tiempo = $intervalo->i . " minuto atrás";
            }
            //Mas de un minuto
            else 
            {
                $mensaje_tiempo = $intervalo->i . " minutos atrás";
            }
        }
        // + Si el intervalo es de 1 segundo o mas atras, pero menos que un minuto
        else
        {
            //Menos que 30 segundos
            if($intervalo->s < 30)
            {
                $mensaje_tiempo = "Justo ahora";
            }
            //30 segundos o mas
            else 
            {
                $mensaje_tiempo = "Hace unos segundos";
            }
        }
         #endregion

         array_push($arreglo_detalles, $mensaje_de);
         array_push($arreglo_detalles, $fila['cuerpo_mensaje']);
         array_push($arreglo_detalles, $mensaje_tiempo);

        return $arreglo_detalles;
    }

    public function obtenerConversaciones()
    {
        $id_usuario_loggeado = $this->objeto_usuario->obtenerIDUsuario();
        $return_string = "";
        // + Agregaremos los nombres de usuario con los que el usuario tuvo conversaciones a este arreglo
        $conversaciones = array();

        $query_obtener_usuarios_mensajes = mysqli_query($this->con, "SELECT mensaje_para, mensaje_de FROM mensajes WHERE mensaje_para='$id_usuario_loggeado' OR mensaje_de='$id_usuario_loggeado' ORDER BY id_mensaje DESC");

        while($fila = mysqli_fetch_array($query_obtener_usuarios_mensajes))
        {
            $contacto_a_pushear = ($fila['mensaje_para'] != $id_usuario_loggeado) ? $fila['mensaje_para'] : $fila['mensaje_de'];
            // + Checamos que el usuario ya no este en el arreglo
            if(!in_array($contacto_a_pushear, $conversaciones))
            {
                array_push($conversaciones, $contacto_a_pushear);
            }
        }

        foreach($conversaciones as $nombreUsuario)
        {
            $objeto_usuario_encontrado = new Usuario($this->con, $nombreUsuario);
            $detalles_ultimo_mensaje = $this->obtenerUltimoMensaje($id_usuario_loggeado, $nombreUsuario);
            $nombreUsuario = $objeto_usuario_encontrado->obtenerNombreUsuario();

            // + $detalles_ultimo_mensaje[1] sera el cuerpo de nuestro mensaje 
            $puntos = (strlen($detalles_ultimo_mensaje[1]) >= 12 ? "..." : "");
            $separar = str_split($detalles_ultimo_mensaje[1], 12);
            $separar = $separar[0] . $puntos; // + Esto lo que hace es cortar el mensaje si es mayor a 12 caracteres y ponerle puntos

            $return_string .= "<a href='messages.php?u=$nombreUsuario'> 
                                    <div class='mensajes_encontrados'>
                                        <img src='" . $objeto_usuario_encontrado->obtenerFotoPerfil() . "' style='border-radius: 5px; margin-right: 5px;'>
                                        " . $objeto_usuario_encontrado->obtenerNombreCompleto() . "
                                        <span class='marca_de_tiempo' id='gris' >" . $detalles_ultimo_mensaje[2] . "</span>
                                        <p id='gris' style='margin: 0; '> " . $detalles_ultimo_mensaje[0] . $separar . "</p>
                                    </div>
                                </a>";
        }
        return $return_string;
    }

    public function obtenerDropdownConversaciones($info, $limite)
    {
        $pagina = $info['pagina'];
        $id_usuario_loggeado = $this->objeto_usuario->obtenerIDUsuario();
        $return_string = "";
        // + Agregaremos los nombres de usuario con los que el usuario tuvo conversaciones a este arreglo
        $conversaciones = array();
        if($pagina == 1)
        {
            $inicio = 0;
        }
        else
        {
            $inicio = ($pagina - 1) * $limite;
        }

        // + Al hacer click en el icono, entonces el usuario habra visto los mensajes y se marcaran como leidos 
        $query_establecer_mensaje_visto = mysqli_query($this->con, "UPDATE mensajes SET visto='si' WHERE mensaje_para='$id_usuario_loggeado'");

        $query_obtener_usuarios_mensajes = mysqli_query($this->con, "SELECT mensaje_para, mensaje_de FROM mensajes WHERE mensaje_para='$id_usuario_loggeado' OR mensaje_de='$id_usuario_loggeado' ORDER BY id_mensaje DESC");

        while($fila = mysqli_fetch_array($query_obtener_usuarios_mensajes))
        {
            $contacto_a_pushear = ($fila['mensaje_para'] != $id_usuario_loggeado) ? $fila['mensaje_para'] : $fila['mensaje_de'];
            // + Checamos que el usuario ya no este en el arreglo
            if(!in_array($contacto_a_pushear, $conversaciones))
            {
                array_push($conversaciones, $contacto_a_pushear);
            }
        }

        $numero_iteraciones = 0; // + Numero de mensajes vistos
        $contador = 1; // + Numero de mensajes publicados

        foreach($conversaciones as $nombreUsuario)
        {

            if($numero_iteraciones++ < $inicio)
            {
                continue;
            }

            if($contador > $limite)
            {
                break;
            }
            else
            {
                $contador++;
            }

            // + Query para verificar si el mensaje fue abierto o no
            $query_mensaje_abierto = mysqli_query($this->con, "SELECT abierto FROM mensajes WHERE mensaje_para='$id_usuario_loggeado' AND mensaje_de='$nombreUsuario' ORDER BY id_mensaje DESC");
            $fila = mysqli_fetch_array(($query_mensaje_abierto));

            // + Todos los mensajes sin leer, se veran de un color diferente
            $estilo_mensaje = (isset($fila['abierto']) && $fila['abierto'] == 'no') ? "background-color: #DDEDFF;" : "";

            $objeto_usuario_encontrado = new Usuario($this->con, $nombreUsuario);
            $detalles_ultimo_mensaje = $this->obtenerUltimoMensaje($id_usuario_loggeado, $nombreUsuario);
            $nombreUsuario = $objeto_usuario_encontrado->obtenerNombreUsuario();

            // + $detalles_ultimo_mensaje[1] sera el cuerpo de nuestro mensaje 
            $puntos = (strlen($detalles_ultimo_mensaje[1]) >= 12 ? "..." : "");
            $separar = str_split($detalles_ultimo_mensaje[1], 12);
            $separar = $separar[0] . $puntos; // + Esto lo que hace es cortar el mensaje si es mayor a 12 caracteres y ponerle puntos

            $return_string .= "<a href='messages.php?u=$nombreUsuario'> 
                                    <div class='mensajes_encontrados' style='" . $estilo_mensaje . "'>
                                        <img src='" . $objeto_usuario_encontrado->obtenerFotoPerfil() . "' style='border-radius: 5px; margin-right: 5px;'>
                                        " . $objeto_usuario_encontrado->obtenerNombreCompleto() . "<br>
                                        <span class='marca_de_tiempo' id='gris' >" . $detalles_ultimo_mensaje[2] . "</span>
                                        <p id='gris' style='margin: 0; '> " . $detalles_ultimo_mensaje[0] . $separar . "</p>
                                    </div>
                                </a>";
        }

        // + Si los mensajes fueron cargados
        if ($contador > $limite)
        {
            $return_string .= "<input type='hidden' class='dropdownSiguientePagina' value='" . ($pagina + 1) . "'>
                               <input type='hidden' class='noMasInfoDropdown' value='false'>";
        }
        else
        {
            $return_string .= "<input type='hidden' class='noMasInfoDropdown' value='true'> <p style='text-align: center;'>No mas mensajes para mostrar!</p>";
        }
        
        return $return_string;
    }

    // + Obtendra el numero de mensajes no leidos
    public function obtenerMensajesNoLeidos()
    {
        $id_usuario_loggeado = $this->objeto_usuario->obtenerIDUsuario();
        $query_obtener_mensajes_no_leidos = mysqli_query($this->con, "SELECT * FROM mensajes WHERE visto='no' AND mensaje_para='$id_usuario_loggeado'");
        // + Retornara el numero de mensajes no leidos
        return mysqli_num_rows($query_obtener_mensajes_no_leidos);
    }
}
?>
