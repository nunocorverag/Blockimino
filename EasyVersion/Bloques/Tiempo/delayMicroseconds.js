Blockly.Blocks['arduino_delayMicroseconds'] = {
    init: function() {
        //Atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        
        this.appendDummyInput()
            .appendField("Pausar todo en microsegundos")
            .appendField(new Blockly.FieldNumber(0, null, null, 1), "PARAM_NUMBER");
        this.setColour('#3f51b5');
        this.setTooltip("Retorna la cantidad de microsegundos especificada que lleva ejecutando Arduino");
        this.getField("PARAM_NUMBER").setMax(1000000);
        this.getField("PARAM_NUMBER").setMin(-1000000);
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour('#aa137d');
    }
  };
  
  Blockly.JavaScript['arduino_delayMicroseconds'] = function(block) {
    const param_number = block.getFieldValue('PARAM_NUMBER');
    return "delayMicroseconds(" + param_number + ");\n";
  };
  