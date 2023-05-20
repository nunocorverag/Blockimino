Blockly.Blocks['serial_flush'] = {
    init: function() {
    this.svgGroup_.setAttribute('data-attribute', 'objects');
    this.svgGroup_.setAttribute('special-attribute', 'serial');
      this.appendDummyInput()
          .appendField("Esperar a desalojar datos del serial");
      this.setPreviousStatement(true, "!arduino_case");
      this.setNextStatement(true, "!arduino_case");
      this.setColour('#006699');
      this.setTooltip("Asegura que termine todos los procesos seriales, recomendable si el serial se comporta de forma no esperada");
    }
  };
  
  Blockly.JavaScript['serial_flush'] = function(block) {
    var code = "Serial.flush();\n";
    return code;
  };