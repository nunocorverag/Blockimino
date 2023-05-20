Blockly.Blocks['pinMode'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'setup');
        this.svgGroup_.setAttribute('special-attribute', 'UNO');

        this.appendDummyInput()
           
            .appendField("(arduino UNO) Elegir pin")
            .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "PIN")
            .appendField("para ")
            .appendField(new Blockly.FieldDropdown([
                ["enviar", "INPUT"],
                ["recibir", "OUTPUT"]
            ]), "MODE")
            .appendField("datos");
        this.setPreviousStatement(true, "setup");
        this.setNextStatement(true, "setup");
        this.setColour('#bb7111');
        this.setTooltip("Establece un pin digital como entrada o como salida.");

    }
};

Blockly.JavaScript['pinMode'] = function (block) {
    var pin = block.getFieldValue('PIN');
    var mode = block.getFieldValue('MODE');
    var code = 'pinMode(' + pin + ', ' + mode + ');\n';
    return code;
};


Blockly.Blocks['pinMode_MEGA'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'setup');
        this.svgGroup_.setAttribute('special-attribute', 'MEGA');

        this.appendDummyInput()
            
            .appendField("(arduino MEGA) Elegir pin")
            .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "PIN")
            .appendField("para")
            .appendField(new Blockly.FieldDropdown([
                ["INPUT", "INPUT"],
                ["OUTPUT", "OUTPUT"]
            ]), "MODE")
            .appendField("datos");
        this.setPreviousStatement(true, "setup");
        this.setNextStatement(true, "setup");
        this.setColour('#bb7111');
        this.setTooltip("Establece un pin digital como entrada o como salida.");

    }
};

Blockly.JavaScript['pinMode_MEGA'] = function (block) {
    var pin = block.getFieldValue('PIN');
    var mode = block.getFieldValue('MODE');
    var code = 'pinMode(' + pin + ', ' + mode + ');\n';
    return code;
};
