// Inicializa el bloque 'arduino_case'
Blockly.Blocks['arduino_case'] = {
  // Función de inicialización del bloque
  init: function() {
      // Agregar atributos
      this.svgGroup_.setAttribute('data-attribute', 'parameters');

      // Agrega un campo de texto para el valor del caso
      this.appendValueInput("VALUE")
          .setCheck(["int", "intval", "short", "shortval", "long", "longval", "char", "charval"])
          .appendField("En caso de: ");
      // Añadir entrada para el cuerpo del bloque case
      this.appendStatementInput("DO")
          .setCheck("!arduino_case")

      // Configurar conexiones del bloque
      this.setPreviousStatement(true, "arduino_case");
      this.setNextStatement(true, "arduino_case");
      // Establecer el color del bloque
      this.setColour('#bbb123');
      this.setTooltip("Bloque usado en para los posibles casos para el switch");
  }
};

// Generar código JavaScript para el bloque 'arduino_case'
Blockly.JavaScript['arduino_case'] = function(block) {
  // Obtiene el valor del caso
  const value = Blockly.JavaScript.valueToCode(block, 'VALUE', Blockly.JavaScript.ORDER_ATOMIC);

  // Obtiene el contenido del cuerpo del bloque case
  const doCode = Blockly.JavaScript.statementToCode(block, 'DO');

  // Genera el código JavaScript para el bloque case con los valores obtenidos
  const code = `case ${value}:\n`
      + doCode
      + 'break;\n';
  // Retorna el código generado
  return code;
};
