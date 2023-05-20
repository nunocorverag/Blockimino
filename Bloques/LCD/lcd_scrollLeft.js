Blockly.Blocks['LCDscrollLeft'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'LCD');

        this.appendDummyInput()
            .appendField("lcd.scrollDisplayLeft()");
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour('#c50000');
        this.setTooltip("Hace scroll hacia la izquierda");
    }
};

Blockly.JavaScript['LCDscrollLeft'] = function (block) {
    const fixedText = "lcd.scrollDisplayLeft();";
    const code = fixedText + '\n';
    return code;
};