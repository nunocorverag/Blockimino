Blockly.Blocks['char_value'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameter');
        this.svgGroup_.setAttribute('value-attribute', 'constant');

        this.appendDummyInput()
            .appendField("valor de char ")
            .appendField(new Blockly.FieldTextInput('', this.textInputValidator), "CHAR_VALUE");
        this.setOutput(true, ["char", "Text"]);
        this.setColour("#008000");
        this.setTooltip("Con este bloque puedes asignar un valor de un caracter a las variables char que hayas creado.");
    },

    textInputValidator: function (newValue) {
        // Permitir solo un caracter en el valor de entrada
        if (newValue.length === 1) {
            return newValue;
        } else {
            return null;
        }
    }
};

Blockly.JavaScript['char_value'] = function (block) {
    let charValue = block.getFieldValue('CHAR_VALUE');
    return ['\'' + charValue + '\'', Blockly.JavaScript.ORDER_ATOMIC];
};
