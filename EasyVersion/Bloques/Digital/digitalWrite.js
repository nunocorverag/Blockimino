Blockly.Blocks['arduino_digital_write'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'UNO');

        this.appendDummyInput()
            
            .appendField("(arduino UNO) Escribir valor digital de pin")
            .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO_Digital), "PIN")
            .appendField("to")
            .appendField(new Blockly.FieldDropdown([
                ["HIGH", "HIGH"],
                ["LOW", "LOW"]
            ]), "STATE");
        this.setPreviousStatement(true, '!arduino_case');
        this.setNextStatement(true, '!arduino_case');
        this.setColour('#ffb347');
        this.setTooltip("Establece un valor ALTO o BAJO de salida en un pin digital.");

    }
};

Blockly.JavaScript['arduino_digital_write'] = function (block) {
    var pin = block.getFieldValue('PIN');
    var state = block.getFieldValue('STATE');
    var code = 'digitalWrite(' + pin + ', ' + state + ');\n';
    return code;
};



Blockly.Blocks['MEGA_arduino_digital_write'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'MEGA');

        this.appendDummyInput()
            
            .appendField("(arduino MEGA) Escribir valor digital de pin")
            .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA_Digital), "PIN")
            .appendField("to")
            .appendField(new Blockly.FieldDropdown([
                ["HIGH", "HIGH"],
                ["LOW", "LOW"]
            ]), "STATE");
        this.setPreviousStatement(true, '!arduino_case');
        this.setNextStatement(true, '!arduino_case');
        this.setColour('#ffb347');
        this.setTooltip("Establece un valor ALTO o BAJO de salida en un pin digital.");

    }
};

Blockly.JavaScript['MEGA_arduino_digital_write'] = function (block) {
    var pin = block.getFieldValue('PIN');
    var state = block.getFieldValue('STATE');
    var code = 'digitalWrite(' + pin + ', ' + state + ');\n';
    return code;
};
