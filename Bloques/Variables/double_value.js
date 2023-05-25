Blockly.Blocks['double_value'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameter');
        this.svgGroup_.setAttribute('value-attribute', 'constant');

        this.appendDummyInput()
            .appendField("valor de double ")
            .appendField(new Blockly.FieldNumber(0, -3.4028235e+38, 3.4028235e+38, 1e-16), "VALUE");
        this.setOutput(true, ["double", "Number"]);
        this.setColour("#008000");
        this.setTooltip("Con este bloque puedes asignar un valor double de hasta 32 bits con mayor precisión que float a las variables double que hayas creado.");
    }
};

Blockly.JavaScript['double_value'] = function (block) {
    let doubleValue = block.getFieldValue('VALUE');
    return [doubleValue, Blockly.JavaScript.ORDER_ATOMIC];
};
