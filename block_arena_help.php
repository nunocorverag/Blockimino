<?php
include("includes/header.php");

$query_seleccionar_temas_pagina = mysqli_query($con , "SELECT * FROM temas_pagina");

?>
    <div class="contenedor_ver_info">
        <h4>Información de los bloques</h4>
        <p>Elige el tema y posteriormente un elemento con el que tengas dudas y aqui se proporcionara información acerca de ello</p>
        
        <div class="contenedor_busqueda_info">
            <div class="busqueda_info_input" id="busqueda_info_input">
                <h4 class="h4_info" style="margin-bottom: 0px; width: 100%;">Buscar tema/bloque/informacion</h4>
                <input type="text" name="query_info" placeholder="Buscar tema/bloque/informacion" autocomplete="off" id="input_busqueda_info">
                <div class="info_resultados_container" id="info_resultados_container"></div>
            </div>

            <br>
            <br> 

            <div class="div_superior_busqueda">
                <div class="div_tema_a_buscar">
                    Tema a buscar:
                    <select name="tema_a_buscar" id="tema_a_buscar" onchange="habilitarSelectBloque()" required>
                        <option value="">Elige un tema a buscar</option>
                        <?php
                        while ($fila_temas_pagina = mysqli_fetch_array($query_seleccionar_temas_pagina)) {
                            $nombre_tema = $fila_temas_pagina['nombre_tema'];
                            $id_tema = $fila_temas_pagina['id_tema'];
                            echo "<option value='$id_tema'>$nombre_tema</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="div_bloque_a_buscar">
                    Elemento a buscar
                    <select name="bloque_a_buscar" id="bloque_a_buscar" onchange="busquedaBloque()" disabled>
                    <option value="">Seleccione un tema primero</option>
                    </select>
                </div>
            </div>
            <div class="info_contenedor" id="info_contenedor" style="display: none;">
                <h4 class="h4_info" style="margin-bottom: 0px; border-top: none;">Descripción</h4>
                <div class="div_descripcion">
                </div>
                <h4 class="h4_info" style="margin-bottom: 0px;">Apéndices</h4>
                <div class="div_apendices">
                </div>
                <h4 class="h4_info" style="margin-bottom: 0px;">Imagen</h4>
                <div class="div_imagen">
                </div>
            </div>
        </div>

        <!-- //+ En este script se buscaran los temas -->
        <script>
            $(document).ready(function() {
                // En este script se buscarán los temas
                $('#input_busqueda_info').on('input', function() {
                    var query = $(this).val();
                    $.ajax({
                        url: 'includes/handlers/ajax_search_theme.php',
                        type: 'POST',
                        data: {query:query},
                        success: function(data) {
                            $('#info_resultados_container').html(data);
                            $('.displayResultadoHashtag').click(function(){
                                var nombre_usuario = $(this).find('.hashtag_encontrado').text().trim();
                                $('#buscar_hashtag').val(nombre_usuario);
                                $('#hashtag_resultados_container').empty();
                            });
                        }
                    });
                });

                // En este script se detectará si se hizo clic y se ocultará el elemento
                document.body.onclick = function(event) {
                    var contenedor = document.getElementById("input_busqueda_info");
                    var info_resultados_container = document.getElementById("info_resultados_container");
                    var targetElement = event.target; // Elemento en el que se hizo clic

                    if (targetElement == contenedor || contenedor.contains(targetElement)) {
                        return; // El clic ocurrió dentro del contenedor, no se oculta
                    }

                    contenedor.value = "";
                    info_resultados_container.innerHTML = "";
                };
            });

            // Script para seleccionar los temas
            function seleccionarTema(idElemento) {
                var temaSelect = document.getElementById("tema_a_buscar");
                temaSelect.value = idElemento;
                habilitarSelectBloque(); // Llama a la función para habilitar el select de bloque y cargar los bloques del tema seleccionado
            }

            function seleccionarBloque(id_tema, id_bloque) {
                var temaSelect = document.getElementById("tema_a_buscar");
                var bloqueSelect = document.getElementById("bloque_a_buscar");

                temaSelect.value = id_tema;
                bloqueSelect.disabled = false;

                $.ajax({
                    type: "POST",
                    url: "includes/handlers/ajax_load_blocks.php",
                    data: { temaID: id_tema },
                    success: function (response) {
                        bloqueSelect.innerHTML = response;
                        bloqueSelect.value = id_bloque; // Asignar el valor del bloque seleccionado
                        busquedaBloque();
                    }
                });
            }

            // Este script habilitará el campo de búsqueda de bloque si hay un tema elegido
            function habilitarSelectBloque() {
                var temaSelect = document.getElementById("tema_a_buscar");
                var bloqueSelect = document.getElementById("bloque_a_buscar");
                var temaSeleccionado = temaSelect.value;
                var temaID = temaSelect.value;

                if (temaSeleccionado !== "") {
                    bloqueSelect.disabled = false;
                    cargarBloquesTema(temaSeleccionado); // Llamada a la función para cargar los bloques del tema seleccionado
                } else {
                    bloqueSelect.disabled = true;
                    bloqueSelect.selectedIndex = 0;
                }

                if (temaSeleccionado == "") {
                    // Mostrar imágenes predeterminadas cuando no se ha seleccionado ningún elemento
                    document.getElementById("info_contenedor").style.display = "none"; // Ocultar los contenedores de bloques
                    $(".div_descripcion").html("");
                    $(".div_imagen").html("");
                    $(".div_apendices").html("");
                }
            }

            function cargarBloquesTema(temaID) {
                    var bloqueSelect = document.getElementById("bloque_a_buscar");

                    $.ajax({
                        type: "POST",
                        url: "includes/handlers/ajax_load_blocks.php",
                        data: {temaID:temaID},
                        success: function(response) {
                            bloqueSelect.innerHTML = response;
                            busquedaBloque();
                        }
                    });
                }

            function busquedaBloque() {
                    var temaSelect = document.getElementById("tema_a_buscar");
                    var bloqueSelect = document.getElementById("bloque_a_buscar");
                    var bloqueID = bloqueSelect.value;
                    var temaID = temaSelect.value;

                    $.ajax({
                        type: "POST",
                        url: "includes/handlers/ajax_load_info.php",
                        data: {temaID:temaID, bloqueID:bloqueID},
                        success: function(response) {
                            var info = JSON.parse(response); // Parsear la respuesta JSON
                            $(".div_descripcion").html(info.descripcion);
                            $(".div_imagen").html("<img src='" + info.imagen + "'>");
                            $(".div_apendices").html(info.apendices);
                        }
                    });
                    document.getElementById("info_contenedor").style.display = "block"; // Mostrar los contenedores de bloques
                }
        </script>

    </div>
</div>  <!-- Cierre de div cuerpo_principal -->
</body>
</html>