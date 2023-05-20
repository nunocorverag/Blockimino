Blockly.Blocks['LCDblink'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'LCD');

        this.appendDummyInput()
            .appendField("lcd.blink()");
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour('#c50000');
        this.setTooltip("Hace que el cursor en vez de solido sea parpadeante");
    }
};

Blockly.JavaScript['LCDblink'] = function (block) {
    const fixedText = "lcd.blink();";
    const code = fixedText + '\n';
    return code;
};