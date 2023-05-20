Blockly.Blocks['arduino_interrupt'] = {
    init: function () {
        this.appendValueInput("FUNC_NAME")
            .setCheck(null)
            .appendField("(arduino UNO) Interrupcion en pin")
            .appendField(new Blockly.FieldDropdown([["2", "2"], ["3", "3"]]), "PIN")
            .appendField("ejecutar funcion");
        this.appendDummyInput()
            .appendField("Modo")
            .appendField(new Blockly.FieldDropdown([["RISING", "RISING"], ["FALLING", "FALLING"], ["CHANGE", "CHANGE"], ["LOW", "LOW"]]), "INTERRUPT_MODE");
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour("#494949");
        this.setTooltip("Configura una interrupcion en Arduino en el pin seleccionado, con el modo de interrupción especificado y la función asociada.");
    }
};

Blockly.JavaScript['arduino_interrupt'] = function (block) {
    let pinNumber = block.getFieldValue('PIN');
    let interruptMode = block.getFieldValue('INTERRUPT_MODE');
    let functionName = Blockly.JavaScript.valueToCode(block, 'FUNC_NAME', Blockly.JavaScript.ORDER_ATOMIC);

    // Remover las comillas alrededor del nombre de la funci�n
    functionName = functionName.replace(/['"]+/g, '');

    let code = 'attachInterrupt(digitalPinToInterrupt(' + pinNumber + '), ' + functionName + ', ' + interruptMode + ');\n';
    return code;
};




Blockly.Blocks['MEGA_arduino_interrupt'] = {
    init: function () {
        this.appendValueInput("FUNC_NAME")
            .setCheck(null)
            .appendField("(arduino MEGA) Interrupcion en pin")
            .appendField(new Blockly.FieldDropdown([["2", "2"], ["3", "3"], ["18", "18"], ["19", "19"], ["20", "20"], ["21", "21"]]), "PIN")
            .appendField("ejecutar funcion");
        this.appendDummyInput()
            .appendField("Modo")
            .appendField(new Blockly.FieldDropdown([["RISING", "RISING"], ["FALLING", "FALLING"], ["CHANGE", "CHANGE"], ["LOW", "LOW"]]), "INTERRUPT_MODE");
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour("#494949");
        this.setTooltip("Configura una interrupcion en Arduino en el pin seleccionado, con el modo de interrupción especificado y la función asociada.");
    }
};

Blockly.JavaScript['MEGA_arduino_interrupt'] = function (block) {
    let pinNumber = block.getFieldValue('PIN');
    let interruptMode = block.getFieldValue('INTERRUPT_MODE');
    let functionName = Blockly.JavaScript.valueToCode(block, 'FUNC_NAME', Blockly.JavaScript.ORDER_ATOMIC);

    // Remover las comillas alrededor del nombre de la funci�n
    functionName = functionName.replace(/['"]+/g, '');

    let code = 'attachInterrupt(digitalPinToInterrupt(' + pinNumber + '), ' + functionName + ', ' + interruptMode + ');\n';
    return code;
};
