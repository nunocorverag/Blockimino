// Inicializa el bloque 'arduino_for'
Blockly.Blocks['arduino_for'] = {
  // Función de inicialización del bloque
  init: function() {
    // Agregar atributos
    this.svgGroup_.setAttribute('data-attribute', 'objects');

    // Configura el bloque con campos de texto/variable/número
    this.appendDummyInput()
        .appendField("(Bucle)");
    // Agregar una entrada para el índice de inicio
    this.appendValueInput("START")
        .setCheck("Number")
        .appendField("Iniciar variable i en: ");
    // Agregar un menú para el operador de comparación
    this.appendDummyInput()
        .appendField(' Mientras que i sea: ')
        .appendField(new Blockly.FieldDropdown([["menor", "<"], ["mayor", ">"], ["menor o igual", "<="], ["mayor o igual", ">="], ["igual", "=="], ["distinto a", "!="]]), "OPERATOR");
    // Agregar una entrada para el índice final
    this.appendValueInput("END")
        .setCheck(["Number", "Text", "Boolean", "bool"]);
    // Agregar un menú para la dirección de incremento/decremento
    this.appendDummyInput()
        .appendField(' Por cada iteración i: ')
        .appendField(new Blockly.FieldDropdown([["+1", "++"], ["-1", "--"]]), "DIRECTION");
    // Añadir entrada para el cuerpo del bucle for
    this.appendStatementInput("DO")
        .setCheck('!arduino_case');
    // Configurar conexiones del bloque
    this.setPreviousStatement(true, '!arduino_case');
    this.setNextStatement(true, '!arduino_case');
    // Establecer el color del bloque
    this.setColour('#bbb123');
    this.setTooltip("Realiza un bucle decidiendo como inicia, mientras se cumpla que y que sucede despues de cada iteración");
  },
    // Función para validar el bloque
    onchange: function () {
        let block = this;
        let count = 0;
        // Recorre los bloques superiores para contar los bloques 'arduino_for' padres
        while (block = block.getSurroundParent()) {
          if (block.type === 'arduino_for') {
            count++;
          }
        }
        // Si se han encontrado tres o más bloques 'arduino_for', envía un alerta y elimina el bloque actual
        if (count >= 10) {
          alert("No se permiten 10 o más bloques 'for' anidados");
          this.dispose();
        }
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


