Blockly.Blocks['LCDscrollRight'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'LCD');

        this.appendDummyInput()
            .appendField("lcd.scrollDisplayRight()");
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour('#c50000');
        this.setTooltip("Hace scroll hacia la derecha");
    }
};

Blockly.JavaScript['LCDscrollRight'] = function (block) {
    const fixedText = "lcd.scrollDisplayRight();";
    const code = fixedText + '\n';
    return code;
};