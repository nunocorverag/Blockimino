Blockly.Blocks['serial_available'] = {
    init: function() {
      this.svgGroup_.setAttribute('data-attribute', 'parameters');
      this.svgGroup_.setAttribute('special-attribute', 'serial');
      this.svgGroup_.setAttribute('value-attribute', 'constant');

      this.setOutput(true, 'bool');
      this.appendDummyInput()
          .appendField("Serial esta disponible?");
          //this.setColour('#0077be'); 138B93
          
      this.setColour('#006699');
      this.setTooltip("Retorna true si esta disponible el puerto serial, de lo contrario retorna false");
    }
  };
  
  Blockly.JavaScript['serial_available'] = function(block) {
    var code = 'Serial.available()';
    return [code, Blockly.JavaScript.ORDER_FUNCTION_CALL];
  };
