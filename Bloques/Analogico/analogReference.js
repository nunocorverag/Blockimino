Blockly.Blocks['arduino_analog_reference'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'setup');
        this.appendDummyInput()
            .appendField("analogReference(")
            .appendField(new Blockly.FieldDropdown([
                ["DEFAULT", "DEFAULT"],
                ["INTERNAL", "INTERNAL"],
                ["EXTERNAL", "EXTERNAL"],
                ["INTERNAL1V1", "INTERNAL1V1"],
                ["INTERNAL2V56", "INTERNAL2V56"]
            ]), "TYPE")
            .appendField(")");
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour("#bb7111");
        this.setTooltip("Establece el voltaje de referencia para las entradas analógicas");
    }
};

Blockly.JavaScript['arduino_analog_reference'] = function (block) {
    var type = block.getFieldValue('TYPE');
    var code = 'analogReference(' + type + ');\n';
    return code;
};
