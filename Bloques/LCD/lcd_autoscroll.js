Blockly.Blocks['LCDautoscroll'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'LCD');

        this.appendDummyInput()
            .appendField("lcd.autoscroll()");
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour('#c50000');
        this.setTooltip("Activa que el LCD automaticamente haga scroll");
    }
};

Blockly.JavaScript['LCDautoscroll'] = function (block) {
    const fixedText = "lcd.autoscroll();";
    const code = fixedText + '\n';
    return code;
};