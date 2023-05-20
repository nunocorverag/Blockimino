Blockly.Blocks['LCDcursor'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'LCD');

        this.appendDummyInput()
            .appendField("lcd habilitar cursor");
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour('#c50000');
        this.setTooltip("Hace que el cursor sea visible");
    }
};

Blockly.JavaScript['LCDcursor'] = function (block) {
    const fixedText = "lcd.cursor();";
    const code = fixedText + '\n';
    return code;
};