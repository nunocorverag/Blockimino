// Inicializa el bloque 'arduino_if'
Blockly.Blocks['arduino_if'] = {
    // Función de inicialización del bloque
    init: function() {
    // Agregar atributos
    this.svgGroup_.setAttribute('data-attribute', 'objects');
    
    // Configurar el bloque con campos de texto/variable/número
    this.appendDummyInput()
      .appendField("if");
    // Agregar una entrada para la primera condición
    this.appendValueInput("CONDITION1")
      .setCheck("cagaderos");
    // Agregar un menú para el operador de comparación
    this.appendDummyInput()
      .appendField(new Blockly.FieldDropdown([["<", "<"], [">", ">"], ["<=", "<="], [">=", ">="], ["==", "=="], ["!=", "!="], ["&&", "&&"], ["||", "||"]]), "OPERATOR");
    // Agregar una entrada para la segunda condición
    this.appendValueInput("CONDITION2")
      .setCheck("cagaderos");
    // Añadir entrada para el cuerpo del bloque if
    this.appendStatementInput("DO")
      .setCheck(null);
    // Configurar conexiones del bloque
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    // Establecer el color del bloque
    //this.setColour('#bb1234'); ROJO PIOLAS
    this.setColour('#bbb123');
    }
};
    


// Generar código JavaScript para el bloque 'arduino_if'
Blockly.JavaScript['arduino_if'] = function(block) {
    // Obtiene las condiciones y el operador de comparación
    const condition1 = Blockly.JavaScript.valueToCode(block, 'CONDITION1', Blockly.JavaScript.ORDER_ATOMIC);
    const condition2 = Blockly.JavaScript.valueToCode(block, 'CONDITION2', Blockly.JavaScript.ORDER_ATOMIC);
    const operator = block.getFieldValue('OPERATOR');
    const operatorStr = operator === '&&' || operator === '||' ? ` ${operator} ` : ` ${operator} `;

    // Obtiene el contenido del bucle
    const doCode = Blockly.JavaScript.statementToCode(block, 'DO');

    // Genera el código JavaScript para el bucle while con los valores obtenidos
    const code = `if (${condition1}${operatorStr}${condition2}) {\n`
        + doCode
        + '}\n';
    // Retorna el código generado
    return code;
};