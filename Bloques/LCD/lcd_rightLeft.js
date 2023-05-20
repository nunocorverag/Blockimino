Blockly.Blocks['LCDrightLeft'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'LCD');

        this.appendDummyInput()
            .appendField("lcd.rightToLeft()");
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour('#c50000');
        this.setTooltip("Escribe de derecha a izquierda");
    }
};

Blockly.JavaScript['LCDrightLeft'] = function (block) {
    const fixedText = "lcd.rightToLeft();";
    const code = fixedText + '\n';
    return code;
};