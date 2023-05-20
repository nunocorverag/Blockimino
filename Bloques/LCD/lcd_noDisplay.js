Blockly.Blocks['LCDnoDisplay'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'LCD');

        this.appendDummyInput()
            .appendField("lcd.noDisplay()");
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour('#c50000');
        this.setTooltip("Apaga la pantalla LCD");
    }
};

Blockly.JavaScript['LCDnoDisplay'] = function (block) {
    const fixedText = "lcd.noDisplay();";
    const code = fixedText + '\n';
    return code;
};