// Inicializa el bloque 'arduino_dowhile'
Blockly.Blocks['arduino_dowhile'] = {
    // Función de inicialización del bloque
    init: function() {
    // Agregar atributos
    this.svgGroup_.setAttribute('data-attribute', 'objects');
    // Configura el bloque con campos de texto/variable/número
    this.appendDummyInput()
        .appendField("do");
    // Añadir entrada para el cuerpo del bucle do-while
    this.appendStatementInput("DO")
        .setCheck('!arduino_case');
    // Agregar una entrada para la primera condición
    this.appendValueInput("CONDITION1")
        .appendField("while")
        .setCheck(["Number", "Text", "Boolean", "bool"]);
    // Agregar un menú para el operador de comparación
    this.appendDummyInput()
        .appendField(new Blockly.FieldDropdown([["<", "<"], [">", ">"], ["<=", "<="], [">=", ">="], ["==", "=="], ["!=", "!="]]), "OPERATOR");
    // Agregar una entrada para la segunda condición
    this.appendValueInput("CONDITION2")
        .setCheck(["Number", "Text", "Boolean", "bool"]);
    
    // Configurar conexiones del bloque
    this.setPreviousStatement(true, '!arduino_case');
    this.setNextStatement(true, '!arduino_case');
    // Establecer el color del bloque
    this.setColour('#bbb123');
    this.setTooltip("Realiza un bucle mientras se cumpla la condicion");
}
};

// Generar código JavaScript para el bloque 'arduino_dowhile'
Blockly.JavaScript['arduino_dowhile'] = function(block) {
    // Obtiene las condiciones y el operador de comparación
    const condition1 = Blockly.JavaScript.valueToCode(block, 'CONDITION1', Blockly.JavaScript.ORDER_ATOMIC);
    const condition2 = Blockly.JavaScript.valueToCode(block, 'CONDITION2', Blockly.JavaScript.ORDER_ATOMIC);
    const operator = block.getFieldValue('OPERATOR');
    const operatorStr = operator === '&&' || operator === '||' ? ` ${operator} ` : ` ${operator} `;

    // Obtiene el contenido del bucle
    const doCode = Blockly.JavaScript.statementToCode(block, 'DO');
    
    // Genera el código JavaScript para el bucle do-while con los valores obtenidos
    const code = `do {\n`
        + doCode
        + `} while (${condition1}${operatorStr}${condition2});\n`;
    
    // Retorna el código generado
    return code;
};