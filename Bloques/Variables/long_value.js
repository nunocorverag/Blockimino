Blockly.Blocks['long_value'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameter');
        this.svgGroup_.setAttribute('value-attribute', 'constant');

        this.appendDummyInput()
            .appendField("valor de long ")
            .appendField(new Blockly.FieldNumber(0, -2147483648, 2147483647, 1), "LONG_VALUE");
        this.setOutput(true, ["long", "Number"]);
        this.setColour("#008000");
        this.setTooltip("Con este bloque puedes asignar un valor entero de hasta 32 bits a las variables long que hayas creado.");
    }
};

Blockly.JavaScript['long_value'] = function (block) {
    let longValue = block.getFieldValue('LONG_VALUE');
    return [longValue, Blockly.JavaScript.ORDER_ATOMIC];
};
