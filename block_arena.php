<?php
require 'config/config.php';

if(isset($_SESSION['id_usuario']))
{
    // - Esta variable guarda el id del usuario
    $id_usuario_loggeado = $_SESSION['id_usuario'];

    // - Guardamos en esta variable la query de todos los datos del usuario loggeado
    $query_detalles_usuario = mysqli_query($con, "SELECT * FROM usuarios WHERE id_usuario='$id_usuario_loggeado'");
    // - Guardamos en esta variable 
    $fila_detalles_usuario = mysqli_fetch_array($query_detalles_usuario);
    // - Esta variable guardara el nombre de usuario para poder hacer querys mas adelante
    $usuario_loggeado = $fila_detalles_usuario['username'];

    $query_verificar_que_usuario_no_este_sancionado = mysqli_query($con, "SELECT * FROM sanciones WHERE id_usuario_sancionado='$id_usuario_loggeado' AND sancion_eliminada='no'");
    if(mysqli_num_rows($query_verificar_que_usuario_no_este_sancionado) > 0)
    {
        header("Location: sanctioned.php?username=" . $usuario_loggeado);
    }
    else
    {            
        $tipo_usuario = $fila_detalles_usuario['tipo'];
    }
    if(isset($_GET['project']))
    {
        $project = $_GET['project'];
        $query_comprobar_proyecto = mysqli_query($con, "SELECT * FROM proyectos WHERE nombre_proyecto='$project' AND id_usuario_proyecto='$id_usuario_loggeado'");
        if(mysqli_num_rows($query_comprobar_proyecto) > 0)
        {
            $fila_obtener_link = mysqli_fetch_array($query_comprobar_proyecto);
            $link_proyecto = $fila_obtener_link['link_proyecto'];

            ?>
            <!DOCTYPE HTML>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Blockimino</title>
                <script src="scripts.js"></script><!-- todos los scripts de los bloques -->
                <script src="Libraries/LibreriasBlocklyOficial/blockly_compressed.js"></script><!-- librerias oficiales de blockly -->
                <script src="Libraries/LibreriasBlocklyOficial/blocks_compressed.js"></script>
                <script src="Libraries/LibreriasBlocklyOficial/javascript_compressed.js"></script>

                <link rel="stylesheet" type="text/css" href="Libraries/styles.css">
                <link rel="stylesheet" type="text/css" href="Libraries/nav-bar.css">
                <link rel="stylesheet" type="text/css" href="Libraries/menu.css">

                <script src="Libraries/jquery-3.6.0.min.js"></script>
                <script src="Libraries/verify_arduino.js"></script>
                <script src="Libraries/export_arduino.js"></script>
                <script src="Libraries/export_project.js"></script>
                <script src="Libraries/load.js"></script>
                <script src="Libraries/feedback.js"></script>
                <script src="Libraries/dropzone.js"></script>
                <script src="Libraries/menu-click.js"></script>
                <script src="Libraries/trashcan.js"></script>

                <link rel="icon" href="Libraries/images/blockimino.png">
            </head>

            <body>
                <div id="header">
                    <div id="nav">
                        <div class="logo" style="margin-left: 16px">
                            <a class="home" onclick="window.open('home.php', '_blank')" style="cursor: pointer;"></a>
                        </div>  
                        <ul>
                            <li class="dropdown">
                                <a>Archivo</a>
                                <div class="dropdown-content">
                                    <a id="export_text">Descargar Arduino</a>
                                    <a id="export_xml">Descargar Projecto</a>
                                    <a id="load_text">Cargar Projecto</a>
                                </div>
                            </li>
                            <li class="dropdown">
                                <a>Ayuda</a>
                                <div class="dropdown-content">
                                    <a id="help">Recomendaciones</a>
                                    <a id="helpDiv"><Links onclick="window.open('block_arena_help.php', '_blank')">Ayuda bloques</Links></a>
                                    <a id="manual" href="assets/files/manual_de_uso_de_arena_blockimino.pdf" download>Manual de uso</a>
                                </div>
                            </li>
                            <li><a id="verify_text">Verificar</a>
                            <li><a id="buttonOpen"><Links onclick="window.open('EasyVersion/EasyVersionBlockArena.php?project=<?php echo $project?>', '_blank')">Modo Principiante</Links></a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Blockly -->
                <!-- ToolBox -->
                <div id="content">
                    <div id="blocklyDiv">
                        <!-- Categorias y bloques-->
                        <xml id="toolbox">
                            <category name="Estructuras de Control" colour="#bbb123" css-class="categoryAnalogico">
                                <block type="arduino_for"></block>
                                <block type="arduino_while"></block>
                                <block type="arduino_dowhile"></block>
                                <block type="arduino_if"></block>
                                <block type="arduino_ifelse"></block>
                                <block type="arduino_switch"></block>
                                <block type="arduino_case"></block>
                            </category>
                            <category name="Variables" colour="green" css-class="categoryDigital">
                                <block type="create_bool"></block>
                                <block type="create_char"></block>
                                <block type="create_string"></block>
                                <block type="create_int"></block>
                                <block type="create_long"></block>
                                <block type="create_short"></block>
                                <block type="create_float"></block>
                                <block type="create_double"></block>

                                <block type="bool_list"></block>
                                <block type="char_list"></block>
                                <block type="string_list"></block>
                                <block type="int_list"></block>
                                <block type="long_list"></block>
                                <block type="short_list"></block>
                                <block type="float_list"></block>
                                <block type="double_list"></block>

                                <block type="bool_value"></block>
                                <block type="char_value"></block>
                                <block type="string_value"></block>
                                <block type="int_value"></block>
                                <block type="long_value"></block>
                                <block type="short_value"></block>
                                <block type="float_value"></block>
                                <block type="double_value"></block>
                            </category>
                            <category name="Operadores" colour="#663508" css-class="categoryDigital">
                                <block type="arithmetic_operator"></block>
                                <block type="boolean_operator"></block>
                                <block type="updater_operator"></block>
                            </category>
                            <category name="Matematicas" colour="blue" css-class="categoryDigital">
                                <block type="arduino_abs"></block>
                                <block type="arduino_constrain"></block>
                                <block type="arduino_map"></block>
                                <block type="arduino_max"></block>
                                <block type="arduino_min"></block>
                                <block type="arduino_pow"></block>
                                <block type="arduino_sq"></block>
                                <block type="arduino_sqrt"></block>
                                <block type="arduino_random"></block>
                            </category>
                            <category name="Digital" colour="#ffb347" css-class="categoryDigital">
                                <block type="arduino_digital_read"></block>
                                <block type="MEGA_arduino_digital_read"></block>
                                <block type="arduino_digital_write"></block>
                                <block type="MEGA_arduino_digital_write"></block>
                            </category>
                            <category name="Analogico" colour="#8F00FF" css-class="categoryDigital">
                                <block type="arduino_analog_read"></block>
                                <block type="MEGA_arduino_analog_read"></block>
                                <block type="arduino_analog_write"></block>
                                <block type="MEGA_arduino_analog_write"></block>
                            </category>
                            <category name="Funciones" colour="gray" css-class="categoryDigital">
                                <block type="create_function"></block>
                                <block type="create_void_function"></block>
                                <block type="function_list"></block>
                                <block type="function_list_value"></block>
                                <block type="arduino_interrupt"></block>
                                <block type="MEGA_arduino_interrupt"></block>
                            </category>
                            <category name="Tiempo" colour="#aa137d" css-class="categoryDigital">
                                <block type="arduino_delay"></block>
                                <block type="arduino_delayMicroseconds"></block>
                                <block type="arduino_milis"></block>
                                <block type="arduino_micros"></block>
                            </category>
                            <category name="Serial" colour="#138B93" css-class="categoryDigital">
                                <block type="serial_begin"></block>
                                <block type="serial_available"></block>
                                <block type="serial_read"></block>
                                <block type="serial_write"></block>
                                <block type="serial_print"></block>
                                <block type="serial_println"></block>
                                <block type="serial_flush"></block>
                                <block type="serial_end"></block>
                            </category>
                            <category name="LCD" colour="red" css-class="categoryDigital">
                                <block type="includeLCD"></block>
                                <block type="includeLCD_MEGA"></block>
                                <block type="LCDbegin"></block>
                                <block type="LCDclear"></block>
                                <block type="LCDhome"></block>
                                <block type="LCDsetCursor"></block>
                                <block type="LCDwrite"></block>
                                <block type="LCDprint"></block>
                                <block type="LCDcursor"></block>
                                <block type="LCDnoCursor"></block>
                                <block type="LCDblink"></block>
                                <block type="LCDnoBlink"></block>
                                <block type="LCDdisplay"></block>
                                <block type="LCDnoDisplay"></block>
                                <block type="LCDscrollLeft"></block>
                                <block type="LCDscrollRight"></block>
                                <block type="LCDautoscroll"></block>
                                <block type="LCDnoAutoscroll"></block>
                                <block type="LCDleftRight"></block>
                                <block type="LCDrightLeft"></block>
                            </category>
                            <category name="Teclado" colour="#af4c8b" css-class="categoryDigital">
                                <block type="Teclado_include"></block>
                                <block type="Teclado_include_MEGA"></block>
                                <block type="Teclado_read"></block>
                                <block type="Teclado_val"></block>
                            </category>
                            <category name="Sensores" colour="#0c3b49" css-class="categoryDigital">
                                <block type="HC04_include"></block>
                                <block type="HC04_include_MEGA"></block>
                                <block type="HC04_begin"></block>
                                <block type="HC04_dist"></block>
                                <block type="HC04_loop"></block>
                                <block type="LDR_include"></block>
                                <block type="LDR_include_MEGA"></block>
                                <block type="LDR_value"></block>
                                <block type="LDR_loop"></block>
                            </category>
                            <category name="Preparaciones" colour="#bb7111" css-class="categoryAnalogico">
                                <block type="create_define_UNO"></block>
                                <block type="create_define_MEGA"></block>
                                <block type="pinMode"></block>
                                <block type="pinMode_MEGA"></block>
                                <block type="arduino_analog_reference"></block>
                            </category>
                            <div id="dropzone">
                                <!-- iniciar workspace de blockly -->
                                <script>
                                    var workspace = Blockly.inject('blocklyDiv', {
                                    toolbox: document.getElementById('toolbox')
                                    });

                                    // Store the previous position of the block
                                    let previousPosition = null;

                                    // Excluded block types
                                    const excludedBlockTypes = ['create_bool', 'create_int', 'create_float', 'create_double', 'create_char', 'create_string', 'create_long', 'create_short',
                                    'bool_list', 'char_list', 'double_list', 'float_list', 'int_list', 'long_list', 'short_list', 'string_list'];

                                    // Event listener for block move events
                                    Blockly.Events.blockMove = function (event) {
                                    const blockId = event.blockId;
                                    const newPosition = event.newCoordinate;

                                    // Get the block
                                    const block = workspace.getBlockById(blockId);

                                    // Check if the block is excluded from the event listener
                                    if (block && excludedBlockTypes.includes(block.type)) {
                                        return;
                                    }

                                    // Check if the block position has changed and it wasn't due to a mouse drag
                                    if (previousPosition && previousPosition.x !== newPosition.x && previousPosition.y !== newPosition.y && !event.isDragging) {
                                        // Block position changed without mouse drag
                                        basicCounter++;
                                    }

                                    // Update the previous position with the new position
                                    previousPosition = newPosition;
                                    };

                                    // Attach the block move event listener
                                    workspace.addChangeListener(Blockly.Events.blockMove);
                                </script>
                            </div>
                        </xml>
                    </div>
                </div>
                <div id="trashcan" ondragover="allow_drop(event)" ondrop="delete_object(event)">
                    <img src="Libraries/images/Trashcan.png">
                </div>

                <script>
                 // Wrap your script code in a function
                function runScripts() {
                    $(document).ready(function() {
                    var fileURL = '<?php echo $link_proyecto ?>'; // This is my file, but CORS restrictions prevent me from loading it. It should work on the web
                    var delay = 700; // Adjust the delay time as needed (in milliseconds)
                    var loadCount = 0;
    
                    function loadWorkspace() {
                        var xhr = new XMLHttpRequest();
                        xhr.open("GET", fileURL, true);
                        xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            var xml = xhr.responseText;
    
                            var dom = Blockly.Xml.textToDom(xml);
                            workspace.clear();
                            Blockly.Xml.domToWorkspace(dom, workspace);
    
                            loadCount++;
    
                            if (loadCount < 2) {
                            setTimeout(loadWorkspace, delay);
                            }
                        }
                        };
                        xhr.send();
                    }
    
                    loadWorkspace();
                    });
                }
                
                // Use setTimeout with a delay of 0 milliseconds to schedule the function execution
                setTimeout(runScripts, 1200);
                
                </script>

            </body>
            
            </html>

            <?php


        }
        else
        {
            ?>
            <script>
                var confirmationMessage = "El proyecto no existe!";
                alert(confirmationMessage);
                window.location.href = "block_arena.php"
            </script>
            <?php
        }
        
    }
    else
    {
        ?>
            <!DOCTYPE HTML>
            <html style="min-width: 800px; min-height: 500px;">
            <head>
                <meta charset="UTF-8">
                <title>Blockimino</title>
                <script src="scripts.js"></script><!-- todos los scripts de los bloques -->
                <script src="Libraries/LibreriasBlocklyOficial/blockly_compressed.js"></script><!-- librerias oficiales de blockly -->
                <script src="Libraries/LibreriasBlocklyOficial/blocks_compressed.js"></script>
                <script src="Libraries/LibreriasBlocklyOficial/javascript_compressed.js"></script>

                <link rel="stylesheet" type="text/css" href="Libraries/styles.css">
                <link rel="stylesheet" type="text/css" href="Libraries/nav-bar.css">
                <link rel="stylesheet" type="text/css" href="Libraries/menu.css">

                <script src="Libraries/jquery-3.6.0.min.js"></script>
                <script src="Libraries/verify_arduino.js"></script>
                <script src="Libraries/export_arduino.js"></script>
                <script src="Libraries/export_project.js"></script>
                <script src="Libraries/load.js"></script>
                <script src="Libraries/feedback.js"></script>
                <script src="Libraries/dropzone.js"></script>
                <script src="Libraries/trashcan.js"></script>

                <link rel="icon" href="Libraries/images/blockimino.png">
            </head>

            <body>
                <div id="header">
                    <div id="nav">
                        <div class="logo" style="margin-left: 16px">
                            <a class="home" onclick="window.open('home.php', '_blank')" style="cursor: pointer;"></a>
                        </div>  
                        <ul>
                            <li class="dropdown">
                                <a>Archivo</a>
                                <div class="dropdown-content">
                                    <a id="export_text">Descargar Arduino</a>
                                    <a id="export_xml">Descargar Projecto</a>
                                    <a id="load_text">Cargar Projecto</a>
                                </div>
                            </li>
                            <li class="dropdown">
                                <a>Ayuda</a>
                                <div class="dropdown-content">
                                    <a id="help">Recomendaciones</a>
                                    <a id="helpDiv"><Links onclick="window.open('block_arena_help.php', '_blank')">Ayuda bloques</Links></a>
                                    <a id="manual" href="assets/files/manual_de_uso_de_arena_blockimino.pdf" download>Manual de uso</a>
                                </div>
                            </li>
                            <li><a id="verify_text">Verificar</a>
                            <li><a id="buttonOpen"><Links onclick="window.open('EasyVersion/EasyVersionBlockArena.php', '_blank')">Modo Principiante</Links></a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Blockly -->
                <!-- ToolBox -->
                <div id="content">
                    <div id="blocklyDiv">
                        <!-- Categorias y bloques-->
                        <xml id="toolbox">
                            <category name="Estructuras de Control" colour="#bbb123" css-class="categoryAnalogico">
                                <block type="arduino_for"></block>
                                <block type="arduino_while"></block>
                                <block type="arduino_dowhile"></block>
                                <block type="arduino_if"></block>
                                <block type="arduino_ifelse"></block>
                                <block type="arduino_switch"></block>
                                <block type="arduino_case"></block>
                            </category>
                            <category name="Variables" colour="green" css-class="categoryDigital">
                                <block type="create_bool"></block>
                                <block type="create_char"></block>
                                <block type="create_string"></block>
                                <block type="create_int"></block>
                                <block type="create_long"></block>
                                <block type="create_short"></block>
                                <block type="create_float"></block>
                                <block type="create_double"></block>

                                <block type="bool_list"></block>
                                <block type="char_list"></block>
                                <block type="string_list"></block>
                                <block type="int_list"></block>
                                <block type="long_list"></block>
                                <block type="short_list"></block>
                                <block type="float_list"></block>
                                <block type="double_list"></block>

                                <block type="bool_value"></block>
                                <block type="char_value"></block>
                                <block type="string_value"></block>
                                <block type="int_value"></block>
                                <block type="long_value"></block>
                                <block type="short_value"></block>
                                <block type="float_value"></block>
                                <block type="double_value"></block>
                            </category>
                            <category name="Operadores" colour="#663508" css-class="categoryDigital">
                                <block type="arithmetic_operator"></block>
                                <block type="boolean_operator"></block>
                                <block type="updater_operator"></block>
                            </category>
                            <category name="Matematicas" colour="blue" css-class="categoryDigital">
                                <block type="arduino_abs"></block>
                                <block type="arduino_constrain"></block>
                                <block type="arduino_map"></block>
                                <block type="arduino_max"></block>
                                <block type="arduino_min"></block>
                                <block type="arduino_pow"></block>
                                <block type="arduino_sq"></block>
                                <block type="arduino_sqrt"></block>
                                <block type="arduino_random"></block>
                            </category>
                            <category name="Digital" colour="#ffb347" css-class="categoryDigital">
                                <block type="arduino_digital_read"></block>
                                <block type="MEGA_arduino_digital_read"></block>
                                <block type="arduino_digital_write"></block>
                                <block type="MEGA_arduino_digital_write"></block>
                            </category>
                            <category name="Analogico" colour="#8F00FF" css-class="categoryDigital">
                                <block type="arduino_analog_read"></block>
                                <block type="MEGA_arduino_analog_read"></block>
                                <block type="arduino_analog_write"></block>
                                <block type="MEGA_arduino_analog_write"></block>
                            </category>
                            <category name="Funciones" colour="gray" css-class="categoryDigital">
                                <block type="create_function"></block>
                                <block type="create_void_function"></block>
                                <block type="function_list"></block>
                                <block type="function_list_value"></block>
                                <block type="arduino_interrupt"></block>
                                <block type="MEGA_arduino_interrupt"></block>
                            </category>
                            <category name="Tiempo" colour="#aa137d" css-class="categoryDigital">
                                <block type="arduino_delay"></block>
                                <block type="arduino_delayMicroseconds"></block>
                                <block type="arduino_milis"></block>
                                <block type="arduino_micros"></block>
                            </category>
                            <category name="Serial" colour="#138B93" css-class="categoryDigital">
                                <block type="serial_begin"></block>
                                <block type="serial_available"></block>
                                <block type="serial_read"></block>
                                <block type="serial_write"></block>
                                <block type="serial_print"></block>
                                <block type="serial_println"></block>
                                <block type="serial_flush"></block>
                                <block type="serial_end"></block>
                            </category>
                            <category name="LCD" colour="red" css-class="categoryDigital">
                                <block type="includeLCD"></block>
                                <block type="includeLCD_MEGA"></block>
                                <block type="LCDbegin"></block>
                                <block type="LCDclear"></block>
                                <block type="LCDhome"></block>
                                <block type="LCDsetCursor"></block>
                                <block type="LCDwrite"></block>
                                <block type="LCDprint"></block>
                                <block type="LCDcursor"></block>
                                <block type="LCDnoCursor"></block>
                                <block type="LCDblink"></block>
                                <block type="LCDnoBlink"></block>
                                <block type="LCDdisplay"></block>
                                <block type="LCDnoDisplay"></block>
                                <block type="LCDscrollLeft"></block>
                                <block type="LCDscrollRight"></block>
                                <block type="LCDautoscroll"></block>
                                <block type="LCDnoAutoscroll"></block>
                                <block type="LCDleftRight"></block>
                                <block type="LCDrightLeft"></block>
                            </category>
                            <category name="Teclado" colour="#af4c8b" css-class="categoryDigital">
                                <block type="Teclado_include"></block>
                                <block type="Teclado_include_MEGA"></block>
                                <block type="Teclado_read"></block>
                                <block type="Teclado_val"></block>
                            </category>
                            <category name="Sensores" colour="#0c3b49" css-class="categoryDigital">
                                <block type="HC04_include"></block>
                                <block type="HC04_include_MEGA"></block>
                                <block type="HC04_begin"></block>
                                <block type="HC04_dist"></block>
                                <block type="HC04_loop"></block>
                                <block type="LDR_include"></block>
                                <block type="LDR_include_MEGA"></block>
                                <block type="LDR_value"></block>
                                <block type="LDR_loop"></block>
                            </category>
                            <category name="Preparaciones" colour="#bb7111" css-class="categoryAnalogico">
                                <block type="create_define_UNO"></block>
                                <block type="create_define_MEGA"></block>
                                <block type="pinMode"></block>
                                <block type="pinMode_MEGA"></block>
                                <block type="arduino_analog_reference"></block>
                            </category>
                            <div id="dropzone">
                                <!-- iniciar workspace de blockly -->
                                <script>
                                    var workspace = Blockly.inject('blocklyDiv', {
                                    toolbox: document.getElementById('toolbox')
                                    });

                                    // Store the previous position of the block
                                    previousPosition = null;

                                    // Excluded block types
                                    excludedBlockTypes = ['create_bool', 'create_int', 'create_float', 'create_double', 'create_char', 'create_string', 'create_long', 'create_short',
                                    'bool_list', 'char_list', 'double_list', 'float_list', 'int_list', 'long_list', 'short_list', 'string_list'];

                                    // Event listener for block move events
                                    Blockly.Events.blockMove = function (event) {
                                    const blockId = event.blockId;
                                    const newPosition = event.newCoordinate;

                                    // Get the block
                                    const block = workspace.getBlockById(blockId);

                                    // Check if the block is excluded from the event listener
                                    if (block && excludedBlockTypes.includes(block.type)) {
                                        return;
                                    }

                                    // Check if the block position has changed and it wasn't due to a mouse drag
                                    if (previousPosition && previousPosition.x !== newPosition.x && previousPosition.y !== newPosition.y && !event.isDragging) {
                                        // Block position changed without mouse drag
                                        basicCounter++;
                                    }

                                    // Update the previous position with the new position
                                    previousPosition = newPosition;
                                    };

                                    // Attach the block move event listener
                                    workspace.addChangeListener(Blockly.Events.blockMove);
                                </script>
                            </div>
                        </xml>
                    </div>
                </div>
                <div id="trashcan" ondragover="allow_drop(event)" ondrop="delete_object(event)">
                    <img src="Libraries/images/Trashcan.png">
                </div>

            </body>
            </html>

        <?php
    }
}
// + Si no encuentra un usuario loggeado, lo va a regresar a la pagina para crear usuario / iniciar sesion
else 
{
    header("Location: index.php");
}