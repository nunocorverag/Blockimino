Blockly.Blocks['arithmetic_operator'] = {
  init: function() {
    this.svgGroup_.setAttribute('data-attribute', 'parameters');
    var OPERATORS = [
      ['+', '+'],
      ['-', '-'],
      ['*', '*'],
      ['/', '/'],
      ['residuo de división', '%']
    ];
    
    this.appendValueInput('OPERAND1')
        .setCheck(["Number", "Text", "bool"]);
    this.appendDummyInput()
        .appendField(new Blockly.FieldDropdown(OPERATORS), 'OPERATOR');
    this.appendValueInput('OPERAND2')
        .setCheck(["Number", "Text", "bool"]);
    this.setOutput(true, 'Number');
    this.setColour('#961e1e');
    this.setTooltip('Realiza una operación aritmetica');
  }
};
  
Blockly.JavaScript['arithmetic_operator'] = function(block) {
  var operator = block.getFieldValue('OPERATOR');
  var operand1 = Blockly.JavaScript.valueToCode(block, 'OPERAND1', Blockly.JavaScript.ORDER_ATOMIC) || 0;
  var operand2 = Blockly.JavaScript.valueToCode(block, 'OPERAND2', Blockly.JavaScript.ORDER_ATOMIC) || 0;

  var code = '(' + operand1 + ' ' + operator + ' ' + operand2 + ')';
  return [code, Blockly.JavaScript.ORDER_ATOMIC];
};