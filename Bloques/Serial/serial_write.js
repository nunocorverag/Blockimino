Blockly.Blocks['serial_write'] = {
    init: function() {
    this.svgGroup_.setAttribute('data-attribute', 'objects');
    this.svgGroup_.setAttribute('special-attribute', 'serial');
    this.appendDummyInput()
    .appendField("Serial.write");
    this.appendValueInput("CONTENT")
    .setCheck(["Number", "Text", "bool"]);
    this.setPreviousStatement(true, '!arduino_case');
    this.setNextStatement(true, '!arduino_case');
    this.setColour('#006699');
    this.setTooltip("Envía datos binarios a través del puerto serie.");
    }
    };
    
    Blockly.JavaScript['serial_write'] = function(block) {
    const content = Blockly.JavaScript.valueToCode(block, 'CONTENT', Blockly.JavaScript.ORDER_ATOMIC);
    const code = `Serial.write(${content});\n`;
    return code;
    };