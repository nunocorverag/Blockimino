Blockly.Blocks['arduino_digital_read'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'UNO');

        this.appendDummyInput()

            .appendField("(arduino UNO) digitalRead pin")
            .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO_Digital), "PIN");
        this.setOutput(true, "Number");
        this.setColour('#ffb347');
        this.setTooltip("Lee el valor de un pin digital especificado, ya sea HIGH o LOW.");

    }
};

Blockly.JavaScript['arduino_digital_read'] = function (block) {
    var pin = block.getFieldValue('PIN');
    var code = 'digitalRead(' + pin + ')';
    return [code, Blockly.JavaScript.ORDER_ATOMIC];
};

Blockly.Blocks['MEGA_arduino_digital_read'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'MEGA');

        this.appendDummyInput()

            .appendField("(arduino MEGA) digitalRead pin")
            .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA_Digital), "PIN");
        this.setOutput(true, "Number");
        this.setColour('#ffb347');
        this.setTooltip("Lee el valor de un pin digital especificado, ya sea HIGH o LOW.");

    }
};

Blockly.JavaScript['MEGA_arduino_digital_read'] = function (block) {
    var pin = block.getFieldValue('PIN');
    var code = 'digitalRead(' + pin + ')';
    return [code, Blockly.JavaScript.ORDER_ATOMIC];
};
