Blockly.Blocks['LDR_loop'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'LDR');

        this.appendDummyInput()
            .appendField("Leer valor de luminosidad LDR");
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        //this.setColour(120);
        this.setColour('#2a2a2a');
        this.setTooltip('Lee la luminosidad del sensor LDR');
    }
};

Blockly.JavaScript['LDR_loop'] = function (block) {
    const fixedText = "valorLuz = analogRead(sensorLuz);";
    return fixedText + '\n';
};
