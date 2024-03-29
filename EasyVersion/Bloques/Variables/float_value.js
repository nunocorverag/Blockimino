Blockly.Blocks['float_value'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameter');
        this.svgGroup_.setAttribute('value-attribute', 'constant');

        this.appendDummyInput()
            .appendField("valor float ")
            .appendField(new Blockly.FieldNumber(0, -3.4028235e+38, 3.4028235e+38, 1e-16), "VALUE");
        this.setOutput(true, ["floatval", "Number"]);
        this.setColour("#008000");
        this.setTooltip("Con este bloque puedes asignar un valor float a las variables float que hayas creado.");
    }
};

Blockly.JavaScript['float_value'] = function (block) {
    let floatValue = block.getFieldValue('VALUE');
    return [floatValue, Blockly.JavaScript.ORDER_ATOMIC];
};
