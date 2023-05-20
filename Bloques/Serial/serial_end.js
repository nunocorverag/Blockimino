Blockly.Blocks['serial_end'] = {
    init: function() {
    this.svgGroup_.setAttribute('data-attribute', 'objects');
    this.svgGroup_.setAttribute('special-attribute', 'serial');
      this.appendDummyInput()
          .appendField("Serial.end()");
      this.setPreviousStatement(true, "!arduino_case");
      this.setNextStatement(true, "!arduino_case");
      this.setColour('#006699');
      this.setTooltip("Detiene la transmisión serial");
    }
  };
  
  Blockly.JavaScript['serial_end'] = function(block) {
    var code = "Serial.end();\n";
    return code;
  };