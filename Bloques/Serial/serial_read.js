Blockly.Blocks['serial_read'] = {
    init: function() {
    this.svgGroup_.setAttribute('data-attribute', 'parameters');
    this.svgGroup_.setAttribute('special-attribute', 'serial');
    this.svgGroup_.setAttribute('value-attribute', 'constant');

    this.setOutput(true, ["Number", "Text", "char"]);
    this.appendDummyInput()
        .appendField("Serial.read()");
    this.setColour('#006699');
    this.setTooltip("Lee un byte de datos del puerto serial y lo retorna como un valor numérico.");
    }
};

Blockly.JavaScript['serial_read'] = function(block) {
    var code = 'Serial.read()';
    return [code, Blockly.JavaScript.ORDER_FUNCTION_CALL];
};
