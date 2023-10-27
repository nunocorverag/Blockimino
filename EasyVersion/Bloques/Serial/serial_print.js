Blockly.Blocks['serial_print'] = {
    init: function() {
    this.svgGroup_.setAttribute('data-attribute', 'objects');
    this.svgGroup_.setAttribute('special-attribute', 'serial');
    this.appendDummyInput()
    .appendField("Imprimir por monitor serial");
    this.appendValueInput("CONTENT")
    .setCheck(["Number", "Text", "bool"]);
    this.setPreviousStatement(true, '!arduino_case');
    this.setNextStatement(true, '!arduino_case');
    this.setColour('#006699');
    this.setTooltip("Escribe en el monitor serial");
    }
    };
    
    Blockly.JavaScript['serial_print'] = function(block) {
    const content = Blockly.JavaScript.valueToCode(block, 'CONTENT', Blockly.JavaScript.ORDER_ATOMIC);
    const code = `Serial.print(${content});\n`;
    return code;
    };