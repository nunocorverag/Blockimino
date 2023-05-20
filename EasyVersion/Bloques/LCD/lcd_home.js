Blockly.Blocks['LCDhome'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'LCD');

        this.appendDummyInput()
            .appendField("lcd regresar cursor a inicio");
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour('#c50000');
        this.setTooltip("Regresa el cursor a la pocisión inicial");
    }
};

Blockly.JavaScript['LCDhome'] = function (block) {
    const fixedText = "lcd.home();";
    const code = fixedText + '\n';
    return code;
};