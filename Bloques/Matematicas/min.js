Blockly.Blocks['arduino_min'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameter');
        this.svgGroup_.setAttribute('value-attribute', 'constant');

        this.appendDummyInput()
            .appendField("min(");
        this.appendValueInput("VALUE1")
            .setCheck("Number");
        this.appendDummyInput()
            .appendField(", ");
        this.appendValueInput("VALUE2")
            .setCheck("Number");
        this.appendDummyInput()
            .appendField(")");
        this.setInputsInline(true);
        this.setOutput(true, "Number");
        this.setColour(230);
        this.setTooltip("Devuelve el valor más pequeúo entre dos números.");
    }
};

Blockly.JavaScript['arduino_min'] = function (block) {
    var value1 = Blockly.JavaScript.valueToCode(block, 'VALUE1', Blockly.JavaScript.ORDER_ATOMIC);
    var value2 = Blockly.JavaScript.valueToCode(block, 'VALUE2', Blockly.JavaScript.ORDER_ATOMIC);
    var code = 'min(' + value1 + ', ' + value2 + ')';
    return [code, Blockly.JavaScript.ORDER_FUNCTION_CALL];
};

//acepta numeros y variables numericas
//se usa en setup, loop y global