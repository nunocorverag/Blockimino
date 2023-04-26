Blockly.Blocks['arduino_digital_write'] = {
    init: function () {
        this.appendDummyInput()
            ///Añadir imagen con link
            .appendField(new Blockly.FieldImage("Libraries/images/flecha.png", 16, 16, "*", function () {
                window.open("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
            }))
            ///
            .appendField("digitalWrite pin")
            .appendField(new Blockly.FieldDropdown(function () {
                let pinNumbers = [];
                for (let i = 0; i <= 13; i++) {
                    pinNumbers.push([i.toString(), i.toString()]);
                }
                return pinNumbers;
            }), "PIN")
            .appendField("to")
            .appendField(new Blockly.FieldDropdown([
                ["HIGH", "HIGH"],
                ["LOW", "LOW"]
            ]), "STATE");
        this.setPreviousStatement(true, null);
        this.setNextStatement(true, null);
        this.setColour(230);
        this.setTooltip("Establece un valor ALTO o BAJO de salida en un pin digital.");
        
    }
};

Blockly.JavaScript['arduino_digital_write'] = function (block) {
    var pin = block.getFieldValue('PIN');
    var state = block.getFieldValue('STATE');
    var code = 'digitalWrite(' + pin + ', ' + state + ');\n';
    return code;
};
