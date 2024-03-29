Blockly.Blocks['LCDprint'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'LCD');

        this.appendValueInput('TEXT')
            .setCheck(["Number", "Text"])
            .appendField("lcd imprimir (con formato)");
        this.appendDummyInput()
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour('#c50000');
        this.setTooltip("Escribe lo asignado en la pantalla LCD con formato, es decir si tenemos una variable sensorValue entonces lcd.print(sensorValue) imprime el valor de este");
    }
};

Blockly.JavaScript['LCDprint'] = function (block) {
    const text = Blockly.JavaScript.valueToCode(block, 'TEXT', Blockly.JavaScript.ORDER_ATOMIC) || '""';
    const code = "lcd.print(" + text + ");\n";
    return code;
};