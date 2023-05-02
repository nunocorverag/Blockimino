<?php
include("includes/header.php");
?>

<!DOCTYPE HTML>
<html lang="es">

<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Blockly coder</title>
    <script src="scripts.js"></script><!-- todos los scripts de los bloques -->
    <script src="Libraries/LibreriasBlocklyOficial/blockly_compressed.js"></script><!-- librerias oficiales de blockly -->
    <script src="Libraries/LibreriasBlocklyOficial/blocks_compressed.js"></script>
    <script src="Libraries/LibreriasBlocklyOficial/javascript_compressed.js"></script>

    <link rel="stylesheet" type="text/css" href="Libraries/styles.css">
    <link rel="stylesheet" type="text/css" href="Libraries/nav-bar.css">
    <link rel="stylesheet" type="text/css" href="Libraries/menu.css">

    <script src="Libraries/jquery-3.6.0.min.js"></script>
    <script src="Libraries/export_arduino.js"></script>
    <script src="Libraries/export_project.js"></script>
    <script src="Libraries/load.js"></script>
    <script src="Libraries/dropzone.js"></script>
    <script src="Libraries/menu-click.js"></script>
    <script src="Libraries/trashcan.js"></script>
    <script src="Libraries/merge.js"></script>
</head>

<body>
    <div id="header">
        <div id="nav">
            <ul>
                <li><a href="#" class="home"></a></li>
                <li class="dropdown">
                    <a href="#">File</a>
                    <div class="dropdown-content">
                        <a href="#" id="export_text">Download Arduino</a>
                        <a href="#" id="export_xml">Dowload Project</a>
                        <a href="#" id="load_text">Load Project</a>
                    </div>
                </li>
                <li><a href="#" id="export_text">Download</a>
                <li><a href="#">Help</a></li>
                <li><a href="#">User</a></li>
            </ul>
        </div>
    </div>

    <!-- Blockly -->
    <!-- ToolBox -->
    <div id="content">
        <div id="blocklyDiv">
            <!-- Categorias y bloques-->
            <xml id="toolbox">
                <category name="Logica" colour="#bbb123" css-class="categoryAnalogico">
                    <block type="arduino_for"></block>
                    <block type="arduino_while"></block>
                    <block type="arduino_dowhile"></block>
                    <block type="arduino_if"></block>
                </category>
                <category name="Simples" colour="green" css-class="categoryDigital">
                    <block type="simple_text"></block>
                </category>
                <category name="Parametros" colour="blue" css-class="categoryDigital">
                    <block type="param_int"></block>
                    <block type="param_float"></block>
                    <block type="param_string"></block>
                    <block type="create_var"></block>
                    <block type="var_list"></block>
                    <block type="int_var"></block>
                    <block type="ctest"></block>
                    <block type="jtest"></block>
                </category>
                <category name="Set up" colour="#bb7111" css-class="categoryAnalogico">
                    <block type="group_block"></block>
                </category>

                <category name="Digital" colour="#bbb123" css-class="categoryAnalogico">
                    <block type="arduino_digital_read"></block>
                    <block type="arduino_digital_write"></block>
                    <block type="arduino_pin_mode"></block>


                </category>

                <div id="dropzone">
                    <!-- iniciar workspace de blockly -->
                    <script>
                        var workspace = Blockly.inject('blocklyDiv', {
                            toolbox: document.getElementById('toolbox')
                        });
                        workspace.addChangeListener(checkIntVarValueInput);
                        ///
                        verify = 0;
                        function checkIntVarValueInput() {

                            // Obtener todos los bloques en el área de trabajo
                            const workspace = Blockly.getMainWorkspace();
                            const allBlocks = workspace.getAllBlocks();

                            // Filtrar solo los bloques 'int_var'
                            const intVarBlocks = allBlocks.filter(block => block.type === 'int_var');

                            // Iterar sobre los bloques 'int_var'
                            intVarBlocks.forEach(block => {
                                // Obtener la entrada de valor del bloque actual
                                const valueInput = block.getInputTargetBlock('VALUE_INPUT');

                                // Si la entrada de valor existe y su campo 'PARAM' es igual a "m", mostrar una alerta param es el especifico de string
                                if (valueInput && valueInput.getFieldValue('PARAM_NUMBER') != null || valueInput && valueInput.getFieldValue('PARAM') != null) {
                                    if (verify == 0) {
                                        alert('Se encontró un bloque int_var con valor de entrada igual a "m"');
                                        verify = 1;
                                    }
                                    if (verify == 1) {
                                        Blockly.getMainWorkspace().undo(false);
                                        //Blockly.getMainWorkspace().undo(false);
                                        verify = 0;
                                    }
                                }


                            });
                        }
                        ///
                    </script>
                </div>
            </xml>
        </div>
    </div>
    <div id="trashcan" ondragover="allow_drop(event)" ondrop="delete_object(event)">
        <img src="Libraries/images/Trashcan.png">
    </div>
    <script src="Bloques/Pruebas/mi_bloque.js"></script>
    <!-- digital -->
    <script src="Bloques/categorias/Digital/digitalRead.js"></script>
    <script src="Bloques/categorias/Digital/digitalWrite.js"></script>
    <script src="Bloques/categorias/Digital/pinMode.js"></script>
</body>
</html>
