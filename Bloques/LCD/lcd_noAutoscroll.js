Blockly.Blocks['LCDnoAutoscroll'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'LCD');

        this.appendDummyInput()
            .appendField("lcd.noAutoscroll()");
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour('#c50000');
        this.setTooltip("Desactiva que el LCD automaticamente haga scroll");
    }
};

Blockly.JavaScript['LCDnoAutoscroll'] = function (block) {
    const fixedText = "lcd.noAutoscroll();";
    const code = fixedText + '\n';
    return code;
};