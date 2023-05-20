Blockly.Blocks['LCDleftRight'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'LCD');

        this.appendDummyInput()
            .appendField("lcd.leftToRight()");
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour('#c50000');
        this.setTooltip("Escribe de izquierda a derecha");
    }
};

Blockly.JavaScript['LCDleftRight'] = function (block) {
    const fixedText = "lcd.leftToRight();";
    const code = fixedText + '\n';
    return code;
};