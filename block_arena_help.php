<?php
include("includes/header.php");

$query_seleccionar_temas_pagina = mysqli_query($con , "SELECT * FROM temas_pagina");

?>
    <div class="contenedor_ver_info">
        <h4>Información de los bloques</h4>
        <p>Elige el tema o bloque con el que tengas problemas y aqui se proporcionara información acerca de ello</p>
        <div class="contenedor_busqueda_info">
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
                    Bloque a buscar
                    <select name="bloque_a_buscar" id="bloque_a_buscar" onchange="busquedaBloque()" disabled>
                        <option value="">Seleccione un bloque</option>
                        <!-- Aquí puedes agregar las opciones de los bloques -->
                    </select>
                </div>
            </div>
            <h4 class="h4_info" style="margin-bottom: 0px;">Descripción</h4>
            <div class="div_descripcion">
                <img src='assets/images/block_help_material/texto.png' style='width: 225px; height: 225px;'>
            </div>
            <h4 class="h4_info" style="margin-bottom: 0px;">Apéndices</h4>
            <div class="div_apendices">
                <img src='assets/images/block_help_material/youtube.png' style='width: 225px; height: 225px;'>
            </div>
            <h4 class="h4_info" style="margin-bottom: 0px;">Imagen</h4>
            <div class="div_imagen">
                <img src='assets/images/block_help_material/imagen.png' style='width: 225px; height: 225px;'>
            </div>
        </div>

            

            <!-- //+ Este script habilitara el campo de busqueda de bloque si hay un tema elegido -->
            <script>
            function habilitarSelectBloque() {
                var temaSelect = document.getElementById("tema_a_buscar");
                var bloqueSelect = document.getElementById("bloque_a_buscar");
                var temaSeleccionado = temaSelect.value;
                var temaID = temaSelect.value;;

                if (temaSeleccionado !== "") {
                    bloqueSelect.disabled = false;
                    cargarBloquesTema(temaSeleccionado); // Llamada a la función para cargar los bloques del tema seleccionado
                } else {
                    bloqueSelect.disabled = true;
                    bloqueSelect.selectedIndex = 0;
                }

                if(temaSeleccionado != "")
                {
                    $.ajax({
                        type: "POST",
                        url: "includes/handlers/ajax_load_info.php",
                        data: {temaID:temaID},
                        success: function(response) {
                            var info = JSON.parse(response); // Parsear la respuesta JSON
                            $(".div_descripcion").html(info.descripcion);
                            $(".div_imagen").html("<img src='" + info.imagen + "'>");
                            $(".div_apendices").html(info.apendices);
                        }
                    });
                }
                else
                {
                    // Mostrar imágenes predeterminadas cuando no se ha seleccionado ningún elemento
                    $(".div_descripcion").html("<img src='assets/images/block_help_material/texto.png' style='width: 225px; height: 225px;'>");
                    $(".div_imagen").html("<img src='assets/images/block_help_material/imagen.png' style='width: 225px; height: 225px;'>");
                    $(".div_apendices").html("<img src='assets/images/block_help_material/youtube.png' style='width: 225px; height: 225px;'>");
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
                    }
                });
            }

            function busquedaBloque(temaID) {
                var temaSelect = document.getElementById("tema_a_buscar");
                var bloqueSelect = document.getElementById("bloque_a_buscar");
                var bloqueSelect = bloqueSelect.value;
                var temaID = temaSelect.value;

                if(bloqueSelect === "")
                {
                    bloqueID = -1;
                }
                else
                {
                    bloqueID = bloqueSelect;
                }

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
            }
        </script>

    </div>
</div>  <!-- Cierre de div cuerpo_principal -->
</body>
</html>