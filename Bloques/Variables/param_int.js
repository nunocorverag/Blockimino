Blockly.Blocks['param_int'] = {
  init: function() {
      //Atributos
      this.svgGroup_.setAttribute('data-attribute', 'parameters');
      
      this.appendDummyInput()
          .appendField("int:")
          .appendField(new Blockly.FieldNumber(0, null, null, 1), "PARAM_NUMBER");
      this.setOutput(true, null);
      this.setColour(230);
      this.getField("PARAM_NUMBER").setMax(1000000);
      this.getField("PARAM_NUMBER").setMin(-1000000);
  }
};

Blockly.JavaScript['param_int'] = function(block) {
  const param_number = block.getFieldValue('PARAM_NUMBER');
  return [param_number, Blockly.JavaScript.ORDER_ATOMIC];
};