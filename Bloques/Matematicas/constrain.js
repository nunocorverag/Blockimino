Blockly.Blocks['arduino_constrain'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameter');
        this.svgGroup_.setAttribute('value-attribute', 'constant');

        this.appendDummyInput()
            .appendField("constrain(");
        this.appendValueInput("VALUE")
            .setCheck("Number");
        this.appendDummyInput()
            .appendField(", ");
        this.appendValueInput("LOWER_BOUND")
            .setCheck("Number");
        this.appendDummyInput()
            .appendField(", ");
        this.appendValueInput("UPPER_BOUND")
            .setCheck("Number");
        this.appendDummyInput()
            .appendField(")");
        this.setInputsInline(true);
        this.setOutput(true, "Number");
        this.setColour(230);
        this.setTooltip("Restringe un número dentro de un rango.");
    }
};

Blockly.JavaScript['arduino_constrain'] = function (block) {
    var value = Blockly.JavaScript.valueToCode(block, 'VALUE', Blockly.JavaScript.ORDER_ATOMIC);
    var lower_bound = Blockly.JavaScript.valueToCode(block, 'LOWER_BOUND', Blockly.JavaScript.ORDER_ATOMIC);
    var upper_bound = Blockly.JavaScript.valueToCode(block, 'UPPER_BOUND', Blockly.JavaScript.ORDER_ATOMIC);
    var code = 'constrain(' + value + ', ' + lower_bound + ', ' + upper_bound + ')';
    return [code, Blockly.JavaScript.ORDER_FUNCTION_CALL];
};

//acepta numeros y variables numericas
//se usa en setup, loop y global