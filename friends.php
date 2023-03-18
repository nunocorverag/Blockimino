<!-- Sera la lista de amigos que tiene un usuario -->
<?php
//Incluimos el archivo de config.php
require 'config/config.php';
include("includes/classes/Usuario.php");

//! Aqui hay un fallo con este reqf
if(isset($_SESSION['username']) && $_SESSION['tipo'] == "normal" || $_SESSION['tipo'] == "moderador" || $_SESSION['tipo'] == "administrador")
{
    // - Esta variable guardara el nombre de usuario para poder hacer querys mas adelante
    $usuario_loggeado = $_SESSION['username'];
    // - Esta variable guarda el id del usuario
    $id_usuario_loggeado = $_SESSION['id_usuario'];
    // - Guardamos en esta variable la query de todos los datos del usuario loggeado
    $query_detalles_usuario = mysqli_query($con, "SELECT * FROM usuarios WHERE username='$usuario_loggeado'");
    // - Guardamos en esta variable 
    $usuario = mysqli_fetch_array($query_detalles_usuario);
    $tipo_usuario = $_SESSION['tipo'];
}
// + Si no encuentra un usuario loggeado, lo va a regresar a la pagina para crear usuario / iniciar sesion
else 
{
    header("Location: ../index.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>

    <!-- CSS -->
    <!-- Incluimos fontawesome para tener algunos iconos con los cuales trabajar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Incluimos bootstrap para css -->
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <!-- Incluimos el archivo en donde diseñaremos nuestro css -->
    <link rel="stylesheet" href="../assets/css/style.css">



</head>
<body>

     <div class="barra_superior">
        <div class="logo">
            <a href="../home.php">Blockimino</a>
        </div>

        <nav>
            <!-- Nombre del usuario para ir a la pagina del perfil del usuario -->
            <a href="../<?php echo $usuario_loggeado ?>">
                <?php 
                echo $usuario['nombre'];
                ?>
            </a>
            <a href="../home.php">
                <i class="fa-solid fa-house-chimney"></i>
            </a>
            <a href="../messages.php">
                <i class="fa-solid fa-envelope"></i>
            </a>
            <a href="#">
                <i class="fa-regular fa-bell"></i>
            </a>
            <a href="../requests.php">
                <i class="fa-solid fa-users"></i>
            </a>
            <a href="#">
                <i class="fa-solid fa-gear"></i>
            </a>
            <a href="../includes/handlers/logout.php">
                <i class="fa-solid fa-right-from-bracket"></i>            
            </a>
        </nav>
     </div>

     <!-- Este div sera para el cuerpo principal de nuestros otros archivos -->
    <div class="cuerpo_principal">
<?php

if (isset($_GET['perfil_usuario']))
{
    $perfil_nombre_usuario = $_GET['perfil_usuario'];
    $query_obtener_amigos_usuario_perfil = mysqli_query($con, "SELECT id_usuario, lista_amigos FROM usuarios WHERE username='$perfil_nombre_usuario'");
    $fila = mysqli_fetch_array($query_obtener_amigos_usuario_perfil);
    $id_usuario_perfil = $fila['id_usuario'];
    $lista_amigos = $fila['lista_amigos'];
    $lista_amigos_explode = explode(",", $lista_amigos);
}

?>
    <div class="cuerpo_pagina_amigos">
        <h1>Lista de amigos</h1>

        <?php

        foreach($lista_amigos_explode as $amigo)
        {
            if ($amigo != "")
            {
                $query_info_amigo = mysqli_query($con, "SELECT * FROM usuarios WHERE username='$amigo'");
                $fila_info_amigo = mysqli_fetch_array($query_info_amigo);

                $id_amigo = $fila_info_amigo['id_usuario'];
                $usuario = new Usuario($con, $id_usuario_loggeado);
                
                if($id_usuario_loggeado != $id_amigo)
                {
                    if($usuario->obtenerAmigosMutuos($id_amigo) == 1)
                    {
                        $amigos_mutuos = $usuario->obtenerAmigosMutuos($id_amigo) . " Amigo en comun";
                    }
                    else
                    {
                        $amigos_mutuos = $usuario->obtenerAmigosMutuos($id_amigo) . " Amigos en comun";
                    }
                }
                else
                {
                    $amigos_mutuos = "";
                }

                echo "<div class='displayAmigo'>
                        <a href='../" . $fila_info_amigo['username'] . "' style='color: 000'>
                            <div class='fotoPerfilAmigo'>
                                <img src='../". $fila_info_amigo['foto_perfil'] . "'>
                            </div>
                            
                            <div class='infoAmigo'>
                                " . $fila_info_amigo['nombre'] . " " . $fila_info_amigo['apeP'] . " " . $fila_info_amigo['apeM'] . "
                                <p style='margin: 0'> " .$fila_info_amigo['username'] . "</p>
                                <p id='gris'> ". $amigos_mutuos . "</p>
                            </div>
                            </a>
                    </div>";  
            }
        }
        ?>
    </div>
    <div class="amigos">
        <div class="amigos-grid">
            <!-- Se mostrarán los primeros 10 amigos aquí -->
        </div>
        <div class="paginacion">
            <button class="anterior">&lt;</button>
            <button class="siguiente">&gt;</button>
        </div>
    </div>

    <!-- Cargar 10 amigos -->
    <script>
        // Array de amigos (por ejemplo)
        const amigos = <?php echo $lista_amigos_explode ?>

        // Elementos del DOM
        const amigosGrid = document.querySelector('.amigos-grid');
        const botonAnterior = document.querySelector('.anterior');
        const botonSiguiente = document.querySelector('.siguiente');

        // Variables
        let paginaActual = 1;
        const amigosPorPagina = 10;
        const totalPaginas = Math.ceil(amigos.length / amigosPorPagina);

        // Función para mostrar amigos en la página actual
        function mostrarAmigos() {
        amigosGrid.innerHTML = '';

        const indiceInicio = (paginaActual - 1) * amigosPorPagina;
        const indiceFin = indiceInicio + amigosPorPagina;
        const amigosPaginaActual = amigos.slice(indiceInicio, indiceFin);

        amigosPaginaActual.forEach((amigo) => {
            const amigoDiv = document.createElement('div');
            amigoDiv.classList.add('amigo');

            const amigoImagen = document.createElement('img');
            amigoImagen.src = amigo.foto;
            amigoImagen.alt = amigo.nombre;

            const amigoNombre = document.createElement('p');
            amigoNombre.textContent = amigo.nombre;

            amigoDiv.appendChild(amigoImagen);
            amigoDiv.appendChild(amigoNombre);

            amigosGrid.appendChild(amigoDiv);
        });
        }

        // Función para actualizar los botones de paginación
        function actualizarBotones() {
        if (paginaActual === 1) {
            botonAnterior.disabled = true;
        } else {
            botonAnterior.disabled = false;
        }

        if (paginaActual === totalPaginas) {
            botonSiguiente.disabled = true;
        } else {
            botonSiguiente.disabled = false;
        }
        }

        function cargarPaginaAnterior() {
            if (paginaActual > 1) {
                paginaActual--;
                cargarAmigos();
            }
        }

        function cargarPaginaSiguiente() {
            if (paginaActual < numPaginas) {
                paginaActual++;
                cargarAmigos();
            }
        }

        function cargarAmigos() {
        var listaAmigos = document.getElementById("lista-amigos");
        listaAmigos.innerHTML = "";

        // Calcular índices de amigos a mostrar
        var inicio = (paginaActual - 1) * amigosPorPagina;
        var fin = Math.min(inicio + amigosPorPagina, amigos.length);

        // Mostrar amigos
        for (var i = inicio; i < fin; i++) {
            var amigo = amigos[i];
            var amigoHtml = "<div class='amigo'><img src='" + amigo.imagen + "'><p>" + amigo.nombre + "</p></div>";
            listaAmigos.innerHTML += amigoHtml;
        }

        // Actualizar información de paginación
        var infoPaginacion = document.getElementById("info-paginacion");
        infoPaginacion.innerHTML = "Página " + paginaActual + " de " + numPaginas;

        var botonAnterior = document.getElementById("boton-anterior");
        botonAnterior.disabled = paginaActual <= 1;

        var botonSiguiente = document.getElementById("boton-siguiente");
        botonSiguiente.disabled = paginaActual >= numPaginas;
        }

    </script>

</div>   
</body>
</html>