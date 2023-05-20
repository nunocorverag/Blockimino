Blockly.Blocks['LCDdisplay'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'LCD');

        this.appendDummyInput()
            .appendField("lcd.display()");
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour('#c50000');
        this.setTooltip("Activa la pantalla LCD");
    }
};

Blockly.JavaScript['LCDdisplay'] = function (block) {
    const fixedText = "lcd.display();";
    const code = fixedText + '\n';
    return code;
};