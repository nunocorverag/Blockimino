Blockly.Blocks['param_string'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'parameters');

        this.appendDummyInput()
            .appendField("string:")
            .appendField(new Blockly.FieldTextInput("A1"), "PARAM");
        this.setOutput(true, null);
        this.setColour(230);

    }
};

Blockly.JavaScript['param_string'] = function (block) {
    const param = block.getFieldValue('PARAM');
    return [param, Blockly.JavaScript.ORDER_ATOMIC];
};

