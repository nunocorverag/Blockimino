Blockly.Blocks['Teclado_read'] = {
    init: function() {
        //Atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'Teclado');

        this.appendDummyInput()
            .appendField("Obtener valor del Teclado")
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour("#af4c8b");
        this.setTooltip('Espera hasta recibir el valor del teclado y lo guarda');
    }
  };
  
  Blockly.JavaScript['Teclado_read'] = function(block) {
    const fixedText = "Key = keypad.waitForKey();";
    return fixedText + '\n';
  };