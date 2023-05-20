Blockly.Blocks['arduino_sqrt'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameter');
        this.svgGroup_.setAttribute('value-attribute', 'constant');

        this.appendDummyInput()
            .appendField("sqrt(");
        this.appendValueInput("VALUE")
            .setCheck("Number");
        this.appendDummyInput()
            .appendField(")");
        this.setInputsInline(true);
        this.setOutput(true, "Number");
        this.setColour(230);
        this.setTooltip("Calcula la raíz cuadrada de un número.");
    }
};

Blockly.JavaScript['arduino_sqrt'] = function (block) {
    var value = Blockly.JavaScript.valueToCode(block, 'VALUE', Blockly.JavaScript.ORDER_ATOMIC);
    var code = 'sqrt(' + value + ')';
    return [code, Blockly.JavaScript.ORDER_FUNCTION_CALL];
};
//acepta numeros y variables numericas
//se usa en setup, loop y global