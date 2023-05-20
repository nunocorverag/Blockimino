Blockly.Blocks['arduino_analog_read'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameter');
        this.svgGroup_.setAttribute('special-attribute', 'UNO');

        this.appendDummyInput()
            .appendField("(arduino UNO) Leer pin analogico(")
            .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO_Analog), "PIN")
            .appendField(")");
        this.setOutput(true, "Number");
        this.setColour("#5c00a3");
        this.setTooltip("Lee el valor de un pin analógico específico, puedes asignarle otros creados, incluso digitales pero no es recomendable");
    }
};

Blockly.JavaScript['arduino_analog_read'] = function (block) {
    var pin = block.getFieldValue('PIN');
    var code = 'analogRead(' + pin + ')';
    return [code, Blockly.JavaScript.ORDER_ATOMIC];
};


Blockly.Blocks['MEGA_arduino_analog_read'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameter');
        this.svgGroup_.setAttribute('special-attribute', 'MEGA');

        this.appendDummyInput()
            .appendField("(arduino MEGA) Leer pin analogico(")
            .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA_Analog), "PIN")
            .appendField(")");
        this.setOutput(true, "Number");
        this.setColour("#5c00a3");
        this.setTooltip("Lee el valor de un pin analógico específico, puedes asignarle otros creados, incluso digitales pero no es recomendable");
    }
};

Blockly.JavaScript['MEGA_arduino_analog_read'] = function (block) {
    var pin = block.getFieldValue('PIN');
    var code = 'analogRead(' + pin + ')';
    return [code, Blockly.JavaScript.ORDER_ATOMIC];
};
