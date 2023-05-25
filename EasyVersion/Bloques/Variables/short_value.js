Blockly.Blocks['short_value'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameter');
        this.svgGroup_.setAttribute('value-attribute', 'constant');

        this.appendDummyInput()
            .appendField("valor de short ")
            .appendField(new Blockly.FieldNumber(0, -32768, 32767, 1), "VALUE");
        this.setOutput(true, ["short", "Number"]);
        this.setColour("#008000");
        this.setTooltip("Con este bloque puedes asignar un valor short de hasta 16 bits a las variables short que hayas creado.");
    }
};

Blockly.JavaScript['short_value'] = function (block) {
    let shortValue = block.getFieldValue('VALUE');
    return [shortValue, Blockly.JavaScript.ORDER_ATOMIC];
};

