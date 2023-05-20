Blockly.Blocks['LCDnoBlink'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'LCD');

        this.appendDummyInput()
            .appendField("lcd no cursor parpadeante");
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour('#c50000');
        this.setTooltip("Hace que el cursor sea solido nuevamente");
    }
};

Blockly.JavaScript['LCDnoBlink'] = function (block) {
    const fixedText = "lcd.noBlink();";
    const code = fixedText + '\n';
    return code;
};