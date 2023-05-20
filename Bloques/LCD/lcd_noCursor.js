Blockly.Blocks['LCDnoCursor'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'LCD');

        this.appendDummyInput()
            .appendField("lcd.noCursor()");
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour('#c50000');
        this.setTooltip("Esconde el cursor");
    }
};

Blockly.JavaScript['LCDnoCursor'] = function (block) {
    const fixedText = "lcd.noCursor();";
    const code = fixedText + '\n';
    return code;
};