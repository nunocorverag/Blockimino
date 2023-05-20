Blockly.Blocks['arduino_map'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameter');
        this.svgGroup_.setAttribute('value-attribute', 'constant');

        this.appendDummyInput()
            .appendField("map(");
        this.appendValueInput("VALUE")
            .setCheck("Number");
        this.appendDummyInput()
            .appendField(", ");
        this.appendValueInput("FROM_LOW")
            .setCheck("Number");
        this.appendDummyInput()
            .appendField(", ");
        this.appendValueInput("FROM_HIGH")
            .setCheck("Number");
        this.appendDummyInput()
            .appendField(", ");
        this.appendValueInput("TO_LOW")
            .setCheck("Number");
        this.appendDummyInput()
            .appendField(", ");
        this.appendValueInput("TO_HIGH")
            .setCheck("Number");
        this.appendDummyInput()
            .appendField(")");
        this.setInputsInline(true);
        this.setOutput(true, "Number");
        this.setColour(230);
        this.setTooltip("Reasigna un número escalandolo proporcionalmente dentro del rango.");
    }
};

Blockly.JavaScript['arduino_map'] = function (block) {
    var value = Blockly.JavaScript.valueToCode(block, 'VALUE', Blockly.JavaScript.ORDER_ATOMIC);
    var from_low = Blockly.JavaScript.valueToCode(block, 'FROM_LOW', Blockly.JavaScript.ORDER_ATOMIC);
    var from_high = Blockly.JavaScript.valueToCode(block, 'FROM_HIGH', Blockly.JavaScript.ORDER_ATOMIC);
    var to_low = Blockly.JavaScript.valueToCode(block, 'TO_LOW', Blockly.JavaScript.ORDER_ATOMIC);
    var to_high = Blockly.JavaScript.valueToCode(block, 'TO_HIGH', Blockly.JavaScript.ORDER_ATOMIC);
    var code = 'map(' + value + ', ' + from_low + ', ' + from_high + ', ' + to_low + ', ' + to_high + ')';
    return [code, Blockly.JavaScript.ORDER_FUNCTION_CALL];
};

//acepta numeros y variables numericas
//se usa en setup, loop y global