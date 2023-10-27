// Inicializa el bloque 'arduino_switch'
Blockly.Blocks['arduino_switch'] = {
    // Función de inicialización del bloque
    init: function() {
        // Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');

        // Agrega un campo de texto para el valor de la variable a comparar
        this.appendValueInput("VALUE")
            .setCheck(["int", "short", "long", "char"])
            .appendField("switch ");
        // Agregar una entrada para los bloques case
        this.appendStatementInput("CASES")
            .setCheck("arduino_case")
            .appendField("cases:");
        // Agregar una entrada para el bloque default
        this.appendStatementInput("DEFAULT")
            .setCheck("!arduino_case")
            .appendField("default:");
        // Configurar conexiones del bloque
        this.setPreviousStatement(true, '!arduino_case');
        this.setNextStatement(true, '!arduino_case');
        // Establecer el color del bloque
        this.setColour('#bbb123');
        this.setTooltip("Recibe un parametro y segun el caso ejecutará el codigo dentro de cada 'case', si no aplica ninguno entonces ejecutará el default");
    }
};

Blockly.JavaScript['arduino_switch'] = function(block) {
    // Obtiene el valor de la variable a comparar
    const value = Blockly.JavaScript.valueToCode(block, 'VALUE', Blockly.JavaScript.ORDER_ATOMIC);

    // Obtiene el contenido de los bloques case
    const casesCode = Blockly.JavaScript.statementToCode(block, 'CASES');
    // Obtiene el contenido del bloque default
    const defaultCode = Blockly.JavaScript.statementToCode(block, 'DEFAULT');

    // Genera el código JavaScript para el bloque switch con los valores obtenidos
    const code = `switch (${value}) {\n`
        + casesCode
        + '  default:\n'
        + '  ' + defaultCode
        + '  break;\n'
        + '  }\n';
    // Retorna el código generado
    return code;
};