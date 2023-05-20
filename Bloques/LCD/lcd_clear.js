Blockly.Blocks['LCDclear'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'LCD');

        this.appendDummyInput()
            .appendField("lcd.clear()");
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour('#c50000');
        this.setTooltip("Limpia el LCD de texto previo");
    }
};

Blockly.JavaScript['LCDclear'] = function (block) {
    const fixedText = "lcd.clear();";
    const code = fixedText + '\n';
    return code;
};