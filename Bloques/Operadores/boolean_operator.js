Blockly.Blocks['boolean_operator'] = {
  init: function() {
    this.svgGroup_.setAttribute('data-attribute', 'parameters');
    var OPERATORS = [
    ['&&', '&&'],
    ['||', '||']
    ];
    
    this.appendValueInput('OPERAND1')
        .setCheck(["Number", "Text", "bool", "Comparison", "Boolean"]);
    this.appendDummyInput()
        .appendField(new Blockly.FieldDropdown(OPERATORS), 'OPERATOR');
    this.appendValueInput('OPERAND2')
        .setCheck(["Number", "Text", "bool", "Comparison", "Boolean"]);
    this.setOutput(true, 'Boolean');
    this.setColour('#961e1e');
    this.setTooltip('Realiza una operación de comparación booleana');
  }
};
    
Blockly.JavaScript['boolean_operator'] = function(block) {
  var operator = block.getFieldValue('OPERATOR');
  var operand1 = Blockly.JavaScript.valueToCode(block, 'OPERAND1', Blockly.JavaScript.ORDER_ATOMIC) || 'false';
  var operand2 = Blockly.JavaScript.valueToCode(block, 'OPERAND2', Blockly.JavaScript.ORDER_ATOMIC) || 'false';
  
  var code = + operand1 + ' ' + operator + ' ' + operand2;
  return [code, Blockly.JavaScript.ORDER_ATOMIC];
};