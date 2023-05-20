Blockly.Blocks['arduino_random'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameter');
        this.svgGroup_.setAttribute('value-attribute', 'constant');

        this.appendDummyInput()
            .appendField("Generar numero random dentro de limites(");
        this.appendValueInput("MIN")
            .setCheck("Number");
        this.appendDummyInput()
            .appendField(",");
        this.appendValueInput("MAX")
            .setCheck("Number");
        this.appendDummyInput()
            .appendField(")");
        this.setInputsInline(true);
        this.setOutput(true, "Number");
        this.setColour(230);
        this.setTooltip("Genera un número aleatorio entre dos limites (minimo, maximo).");
    }
};

Blockly.JavaScript['arduino_random'] = function (block) {
    var minValue = Blockly.JavaScript.valueToCode(block, 'MIN', Blockly.JavaScript.ORDER_ATOMIC);
    var maxValue = Blockly.JavaScript.valueToCode(block, 'MAX', Blockly.JavaScript.ORDER_ATOMIC);
    var code = 'random(' + minValue + ', ' + maxValue + ')';
    return [code, Blockly.JavaScript.ORDER_FUNCTION_CALL];
};
//acepta numeros y variables numericas
//se usa en setup, loop y global