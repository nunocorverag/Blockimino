Blockly.Blocks['param_float'] = {
    //SI, ME CAGUE EL FLOAT PERO VAN A SALIR LOS REBOTES AAAAAAAAAAAAAAAAAAAAA
    init: function () {
        // Add attributes
        this.svgGroup_.setAttribute('data-attribute', 'parameters');

        this.appendDummyInput()
            .appendField("float:")
            .appendField(new Blockly.FieldNumber(0), "PARAM_NUMBER");
            this.setOutput(true, "cagaderos"); // set output to cagaderos
        this.setColour(230);
        this.getField("PARAM_NUMBER").setMax(1000000);
        this.getField("PARAM_NUMBER").setMin(-1000000);
    }
};

Blockly.JavaScript['param_float'] = function (block) {
    const param_number = block.getFieldValue('PARAM_NUMBER');
    return [param_number, Blockly.JavaScript.ORDER_ATOMIC];
};
