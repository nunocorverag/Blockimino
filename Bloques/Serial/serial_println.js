Blockly.Blocks['serial_println'] = {
    init: function() {
    this.svgGroup_.setAttribute('data-attribute', 'objects');
    this.svgGroup_.setAttribute('special-attribute', 'serial');
    this.appendDummyInput()
    .appendField("Serial.println");
    this.appendValueInput("CONTENT")
    .setCheck(["Number", "Text", "bool"]);
    this.setPreviousStatement(true, '!arduino_case');
    this.setNextStatement(true, '!arduino_case');
    this.setColour('#006699');
    this.setTooltip("Escribe en el monitor serial y salta renglon");
    }
    };
    
    Blockly.JavaScript['serial_println'] = function(block) {
    const content = Blockly.JavaScript.valueToCode(block, 'CONTENT', Blockly.JavaScript.ORDER_ATOMIC);
    const code = `Serial.println(${content});\n`;
    return code;
    };