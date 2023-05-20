Blockly.Blocks['arduino_abs'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameter');
        this.svgGroup_.setAttribute('value-attribute', 'constant');

        this.appendDummyInput()
            .appendField("Valor absoluto(");
        this.appendValueInput("VALUE")
            .setCheck("Number");
        this.appendDummyInput()
            .appendField(")");
        this.setInputsInline(true);
        this.setOutput(true, "Number");
        this.setColour(230);
        this.setTooltip("Devuelve el valor absoluto de un número.");
    }
};

Blockly.JavaScript['arduino_abs'] = function (block) {
    var value = Blockly.JavaScript.valueToCode(block, 'VALUE', Blockly.JavaScript.ORDER_ATOMIC);
    var code = 'abs(' + value + ')';
    return [code, Blockly.JavaScript.ORDER_FUNCTION_CALL];
};

//acepta numeros y variables numericas
//se usa en setup, loop y global