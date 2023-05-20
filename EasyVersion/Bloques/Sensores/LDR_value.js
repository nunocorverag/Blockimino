Blockly.Blocks['LDR_value'] = {
    init: function() {
        //Atributos
        this.svgGroup_.setAttribute('data-attribute', 'parameters');
        this.svgGroup_.setAttribute('special-attribute', 'LDR');

        this.appendDummyInput()
            .appendField("Valor de luminosidad LDR")
        this.setOutput(true, ["int", "Number"]);
        this.setColour("#008000");
        this.setTooltip('Utiliza el valor del LDR, idealmente primero obtenerlo y despues usarlo');
    }
  };
  
  Blockly.JavaScript['LDR_value'] = function(block) {
    const fixedText = "valorLuz";
    return [fixedText, Blockly.JavaScript.ORDER_ATOMIC];
  };