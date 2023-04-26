// Inicializa el bloque 'arduino_for'
Blockly.Blocks['arduino_for'] = {
  // Función de inicialización del bloque
  init: function() {
    // Agregar atributos
    this.svgGroup_.setAttribute('data-attribute', 'objects');

    // Configura el bloque con campos de texto/variable/número
    this.appendDummyInput()
        .appendField("for");
    // Agregar una entrada para el índice de inicio
    this.appendValueInput("START")
        .setCheck("Number")
        .appendField("int i = ");
    // Agregar un menú para el operador de comparación
    this.appendDummyInput()
        .appendField(' i ')
        .appendField(new Blockly.FieldDropdown([["<", "<"], [">", ">"], ["<=", "<="], [">=", ">="], ["==", "=="], ["!=", "!="], ["&&", "&&"], ["||", "||"]]), "OPERATOR");
    // Agregar una entrada para el índice final
    this.appendValueInput("END")
        .setCheck("Number");
    // Agregar un menú para la dirección de incremento/decremento
    this.appendDummyInput()
        .appendField(' i ')
        .appendField(new Blockly.FieldDropdown([["++", "++"], ["--", "--"]]), "DIRECTION");
    // Añadir entrada para el cuerpo del bucle for
    this.appendStatementInput("DO")
        .setCheck(null);
    // Configurar conexiones del bloque
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    // Establecer el color del bloque
    this.setColour('#bbb123');
  }
};


// Generar código JavaScript para el bloque 'arduino_for'
Blockly.JavaScript['arduino_for'] = function(block) {
  // Obtiene el índice de inicio, el índice final, el operador de comparación y la dirección de incremento/decremento
  const start = Blockly.JavaScript.valueToCode(block, 'START', Blockly.JavaScript.ORDER_ATOMIC);
  const end = Blockly.JavaScript.valueToCode(block, 'END', Blockly.JavaScript.ORDER_ATOMIC);
  const operator = block.getFieldValue('OPERATOR');
  const direction = block.getFieldValue('DIRECTION');
  
  // Obtiene el contenido del bucle
  const doCode = Blockly.JavaScript.statementToCode(block, 'DO');
  
  // Genera el código JavaScript para el bucle for con los valores obtenidos
  const code = `for (int i = ${start}; i ${operator} ${end}; i${direction}) {\n`
      + doCode
      + '}\n';
  // Retorna el código generado
  return code;
};


