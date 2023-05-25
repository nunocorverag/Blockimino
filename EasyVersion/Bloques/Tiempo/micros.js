Blockly.Blocks['arduino_micros'] = {
    init: function() {
        //Atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        
        this.appendDummyInput()
            .appendField("Cuanto tiempo ha pasado en microsegundos")
            .appendField(new Blockly.FieldNumber(0, null, null, 1), "PARAM_NUMBER");
        this.setColour('#3f51b5');
        this.setTooltip("Detiene los procesos por la cantidad de microsegundos especificada");
        this.getField("PARAM_NUMBER").setMax(1000000);
        this.getField("PARAM_NUMBER").setMin(-1000000);
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour('#aa137d');
    }
  };
  
  Blockly.JavaScript['arduino_micros'] = function(block) {
    const param_number = block.getFieldValue('PARAM_NUMBER');
    return "micros(" + param_number + ");\n";
  };
