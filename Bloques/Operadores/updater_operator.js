Blockly.Blocks['updater_operator'] = {
  init: function() {
    this.svgGroup_.setAttribute('data-attribute', 'objects');

    this.appendValueInput('OPERAND1')
        .setCheck("variable")

    this.appendDummyInput()
        .appendField('=');

    this.appendValueInput('OPERAND2')
        .setCheck(["Number", "Text", "bool"]);

    this.setPreviousStatement(true, '!arduino_case');
    this.setNextStatement(true, '!arduino_case');
    this.setColour('#961e1e');
    this.setTooltip('Actualizar alguna variable con un nuevo valor');
  }
};

Blockly.JavaScript['updater_operator'] = function(block) {
  var operand1 = Blockly.JavaScript.valueToCode(block, 'OPERAND1', Blockly.JavaScript.ORDER_ATOMIC) || 0;
  var operand2 = Blockly.JavaScript.valueToCode(block, 'OPERAND2', Blockly.JavaScript.ORDER_ATOMIC) || 0;

  var code = operand1 + ' ' + '=' + ' ' + operand2 + ';\n';
  return code;
};