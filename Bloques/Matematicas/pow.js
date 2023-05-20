Blockly.Blocks['arduino_pow'] = {
    init: function() {
      this.svgGroup_.setAttribute('data-attribute', 'parameter');
      this.svgGroup_.setAttribute('value-attribute', 'constant');
  
      this.appendDummyInput()
        .appendField("pow(");
      this.appendValueInput("VALUE")
        .setCheck("Number");
      this.appendDummyInput()
        .appendField(",");
      this.appendValueInput("EXPONENT")
        .setCheck("Number");
      this.appendDummyInput()
        .appendField(")");
      this.setInputsInline(true);
      this.setOutput(true, "Number");
      this.setColour(230);
      this.setTooltip("Calcula la potencia del número (numero, exponente).");
    }
  };
  
  Blockly.JavaScript['arduino_pow'] = function(block) {
    var value = Blockly.JavaScript.valueToCode(block, 'VALUE', Blockly.JavaScript.ORDER_ATOMIC);
    var exponent = Blockly.JavaScript.valueToCode(block, 'EXPONENT', Blockly.JavaScript.ORDER_ATOMIC);
    var code = 'pow(' + value + ', ' + exponent + ')';
    return [code, Blockly.JavaScript.ORDER_FUNCTION_CALL];
  };