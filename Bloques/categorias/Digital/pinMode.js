Blockly.Blocks['arduino_pin_mode'] = {
    init: function () {
        this.appendDummyInput()
            ///Añadir imagen con link
            .appendField(new Blockly.FieldImage("Libraries/images/flecha.png", 16, 16, "*", function () {
                window.open("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
            }))
            ///
            .appendField("pinMode pin")
            .appendField(new Blockly.FieldDropdown(function () {
                let pinNumbers = [];
                for (let i = 0; i <= 13; i++) {
                    pinNumbers.push([i.toString(), i.toString()]);
                }
                return pinNumbers;
            }), "PIN")
            .appendField("mode")
            .appendField(new Blockly.FieldDropdown([
                ["INPUT", "INPUT"],
                ["OUTPUT", "OUTPUT"]
            ]), "MODE");
        this.setPreviousStatement(true, null);
        this.setNextStatement(true, null);
        this.setColour(230);
        this.setTooltip("Establece un pin digital como entrada o como salida.");
        
    }
};

Blockly.JavaScript['arduino_pin_mode'] = function (block) {
    var pin = block.getFieldValue('PIN');
    var mode = block.getFieldValue('MODE');
    var code = 'pinMode(' + pin + ', ' + mode + ');\n';
    return code;
};
