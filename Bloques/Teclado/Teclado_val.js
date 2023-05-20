Blockly.Blocks['Teclado_val'] = {
    init: function() {
        //Atributos
        this.svgGroup_.setAttribute('data-attribute', 'parameters');
        this.svgGroup_.setAttribute('special-attribute', 'Teclado');
        
        this.appendDummyInput()
            .appendField("Valor de Teclado")
        this.setOutput(true, ["char", "Number", "Text"]);
        this.setColour("#008000");
        this.setTooltip('Utiliza el valor de teclado, idealmente primero se obtiene y luego se usa');
    }
  };
  
  Blockly.JavaScript['Teclado_val'] = function(block) {
    const fixedText = "valorAnalogoLuz";
    return [fixedText, Blockly.JavaScript.ORDER_ATOMIC];
  };