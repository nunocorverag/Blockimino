Blockly.Blocks['bool_value'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameter');
        this.svgGroup_.setAttribute('value-attribute', 'constant');

        this.appendDummyInput()
            .appendField("valor de bool ")
            .appendField(new Blockly.FieldDropdown([["true", "TRUE"], ["false", "FALSE"]]), "VALUE");
        this.setOutput(true, ["bool", "Text"]);
        this.setColour("#005300");
        this.setTooltip("Con este bloque puedes asignar un valor booleano (verdadero o falso) a las variables bool que hayas creado.");
    }
};

Blockly.JavaScript['bool_value'] = function (block) {
    let boolValue = block.getFieldValue('VALUE');
    return [boolValue.toLowerCase(), Blockly.JavaScript.ORDER_ATOMIC];
};