Blockly.Blocks['string_value'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameter');
        this.svgGroup_.setAttribute('value-attribute', 'constant');

        this.appendDummyInput()
            .appendField('valor de String "')
            .appendField(new Blockly.FieldTextInput(''), 'VALUE')
            .appendField('"');
        this.setOutput(true, ['stringval', 'Text']);
        this.setColour('#008000');
        this.setTooltip('Con este bloque puedes asignar un valor de cadena de caracteres a las variables string que hayas creado.');
    }
};

Blockly.JavaScript['string_value'] = function (block) {
    let stringValue = block.getFieldValue('VALUE');
    return ['"' + stringValue + '"', Blockly.JavaScript.ORDER_ATOMIC];
};
