// Define un nuevo bloque personalizado llamado "group_block".
Blockly.Blocks['group_block'] = {
    // Funci�n de inicializaci�n para el bloque.
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'setup');

        // Agrega una entrada de declaraci�n al bloque y permite cualquier tipo de bloque.
        this.appendStatementInput("STATEMENTS")
            .setCheck(null)
            .appendField("Agrupador"); // Agrega una etiqueta de texto al bloque llamada "Agrupador".
        // Establece el color del bloque en un tono de verde.
        this.setColour('#bb7111');
    }
};

// Define c�mo se generar� el c�digo JavaScript para el bloque "group_block".
Blockly.JavaScript['group_block'] = function (block) {
    /*// Obtiene el c�digo JavaScript generado para los bloques contenidos en la entrada "STATEMENTS".
    const statements = Blockly.JavaScript.statementToCode(block, 'STATEMENTS');
    // Retorna el c�digo JavaScript generado, sin modificaciones adicionales.
    return statements;*/
    const fixedText = "SETAP";
    return fixedText;
};
