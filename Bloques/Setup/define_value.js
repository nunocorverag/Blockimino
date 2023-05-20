Blockly.Blocks['define_value'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameter');
        this.svgGroup_.setAttribute('value-attribute', 'constant');

        this.appendDummyInput()
            .appendField("valor de string ")
            .appendField(new Blockly.FieldTextInput(""), "STRING_VALUE");
        this.setOutput(true, "define"); // Modificado el tipo de conexi�n de salida a "define"
        this.setColour("#bb7111");
        this.setTooltip("Con este bloque puedes asignar un valor al bloque #define."); // Actualizado el tooltip
    }
};

Blockly.JavaScript['define_value'] = function (block) {
    let stringValue = block.getFieldValue('STRING_VALUE');
    return ['"' + stringValue + '"', Blockly.JavaScript.ORDER_ATOMIC];
};
