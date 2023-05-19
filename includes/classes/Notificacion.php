<?php
// Esta clase trabajara con todo lo que tenga que ver con notificaciones
class Notificacion {
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

    public function obtenerNotificacionesNoLeidas()
    {
        $id_usuario_loggeado = $this->objeto_usuario->obtenerIDUsuario();
        $query_obtener_notificaciones_no_leidas = mysqli_query($this->con, "SELECT * FROM notificaciones WHERE vista='no' AND notificacion_para='$id_usuario_loggeado'");
        // + Retornara el numero de notificaciones no
        return mysqli_num_rows($query_obtener_notificaciones_no_leidas);
    }

    public function obtenerNotificaciones($info, $limite)
    {
        // !NOTA HAY QUE TENER CUIDADO CON EL REDIRECCIONAMIENTO ABSOLUTO EN EL HOST
        $src_pagina = 'http://localhost/blockimino/';

        $pagina = $info['pagina'];
        $id_usuario_loggeado = $this->objeto_usuario->obtenerIDUsuario();
        $return_string = "";
        if($pagina == 1)
        {
            $inicio = 0;
        }
        else
        {
            $inicio = ($pagina - 1) * $limite;
        }

        // + Al hacer click en el icono, entonces el usuario habra visto las notificaciones y se marcaran como leidas 
        $query_establecer_notificacion_vista = mysqli_query($this->con, "UPDATE notificaciones SET vista='si' WHERE notificacion_para='$id_usuario_loggeado'");

        $query_notificaciones = mysqli_query($this->con, "SELECT * FROM notificaciones WHERE notificacion_para='$id_usuario_loggeado' ORDER BY id_notificacion DESC");

        if(mysqli_num_rows($query_notificaciones) == 0)
        {
            echo "No tienes notificaciones!";
            return;
        }

        $numero_iteraciones = 0; // + Numero de mensajes vistos
        $contador = 1; // + Numero de mensajes publicados

        while($fila = mysqli_fetch_array($query_notificaciones))
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

            $notificacion_de = $fila['notificacion_de'];
            $fecha_notificacion = $fila['fecha_notificacion'];
            $query_obtener_info_usuarios = mysqli_query($this->con, "SELECT * FROM usuarios WHERE id_usuario='$notificacion_de'");
            $info_usuario = mysqli_fetch_array($query_obtener_info_usuarios);

            #region Periodo de tiempo de los posts
            // - Guardamos la hora y fecha actuales
            $tiempo_actual = date("Y-m-d H:i:s");
            // - Guardamos la hora y fecha actuales en el que se realizo la publicacion
            $fecha_comienzo = new DateTime($fecha_notificacion);
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
                    $mensaje_tiempo = $intervalo->m . " mes " . $dias;
                }
                //Mas de 1 mes
                else
                {
                    $mensaje_tiempo = $intervalo->m . " meses " . $dias;
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

            $abierta = $fila['abierta'];
            // + Todas las notificaciones sin leer, se veran de un color diferente
            $estilo_notificacion = (isset($fila['abierta']) && $fila['abierta'] == 'no') ? "background-color: #DDEDFF;" : "";

            $return_string .= "<a href=' " . $src_pagina . $fila['link'] ."'> 
                                    <div class='displayResultado displayResultadoNotificacion' style='" . $estilo_notificacion . "'>
                                        <div class='fotoPerfilNotificaciones'>
                                            <img src='" . $src_pagina . $info_usuario['foto_perfil'] . "'>
                                        </div>
                                        <p class ='marcaDeTiempo' id='gris'>" . $mensaje_tiempo . "</p>" . $fila['mensaje'] . "
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
            $return_string .= "<input type='hidden' class='noMasInfoDropdown' value='true'> <p style='text-align: center;'>No mas notificaciones para mostrar!</p>";
        }
        
        return $return_string;
    }

    public function insertarNotificacion($id_publicacion, $notificacion_para, $tipo)
    {
        $id_usuario_loggeado = $this->objeto_usuario->obtenerIDUsuario();
        $nombre_usuario_loggeado = $this->objeto_usuario->obtenerNombreCompleto();
        $tiempo_actual = date("Y-m-d H:i:s");

        // + Este switch sera el tipo de notificacion, por ejemplo si fue un comentario, notificar del comentario
        switch($tipo)
        {
            case 'comentario':
                $mensaje = $nombre_usuario_loggeado . " comentó tu publicación";
                break;
            case 'like':
                $mensaje = $nombre_usuario_loggeado . " likeo tu publicación";
                break;
            case 'publicacion_perfil':
                $mensaje = $nombre_usuario_loggeado . " publicó en tu perfil";
                break;
            case 'comentario_perfil':
                $mensaje = $nombre_usuario_loggeado . " comentó en una publicación realizada en tu perfil";
                break;
            case 'comentario_donde_comentaste':
                $mensaje = $nombre_usuario_loggeado . " comentó en una publicación que tu comentaste";
                break;
            case 'amigo_publico':
                $mensaje = "Tu amigo: " . $nombre_usuario_loggeado . " realizó una publicación";
                break;
            case 'seguido_publico':
                $mensaje = $nombre_usuario_loggeado . " (que sigues), realizó una publicación";
                break;
        }

        $link = "publication.php?id=" . $id_publicacion;
        $query_insertar_notificacion = mysqli_query($this->con, "INSERT INTO notificaciones VALUES ('', '$notificacion_para', '$id_usuario_loggeado', '$mensaje', '$link', '$tiempo_actual', 'no', 'no')");
    }
}


?>