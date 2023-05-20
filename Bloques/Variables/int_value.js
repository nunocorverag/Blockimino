Blockly.Blocks['int_value'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameter');
        this.svgGroup_.setAttribute('value-attribute', 'constant');

        this.appendDummyInput()
            .appendField("valor de int ")
            .appendField(new Blockly.FieldNumber(0, -32768, 32767, 1), "INT_VALUE");
        this.setOutput(true, ["int", "Number"]);
        this.setColour("#008000");
        this.setTooltip("Con este bloque puedes asignar un valor entero de hasta 16 bits a las variables int que hayas creado.");
    }
};

Blockly.JavaScript['int_value'] = function (block) {
    let intValue = block.getFieldValue('INT_VALUE');
    return [intValue, Blockly.JavaScript.ORDER_ATOMIC];
};