// jtest block
Blockly.Blocks['jtest'] = {
  init: function() {
    this.appendDummyInput()
        .appendField("jtest");
    this.setOutput(true, "jtest_conn"); // set type argument to "jtest_conn"
    this.setColour(230);
  }
};

Blockly.JavaScript['jtest'] = function(block) {
  var code = '1';
  return [code, Blockly.JavaScript.ORDER_ATOMIC];
};

// ctest block
Blockly.Blocks['ctest'] = {
  init: function() {
    this.appendValueInput("test_input")
        .setCheck("jtest_conn") // set to the same custom string as in jtest block
        .appendField("ctest");
    this.setColour(230);
    this.setTooltip("");
    this.setHelpUrl("");
  }
};

Blockly.JavaScript['ctest'] = function(block) {
  var value_test_input = Blockly.JavaScript.valueToCode(block, 'test_input', Blockly.JavaScript.ORDER_ATOMIC);
  var code = 'ctest(' + value_test_input + ');\n';
  return code;
};