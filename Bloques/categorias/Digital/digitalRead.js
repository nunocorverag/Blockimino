Blockly.Blocks['arduino_digital_read'] = {
    init: function () {
        this.appendDummyInput()
            ///Añadir imagen con link
            .appendField(new Blockly.FieldImage("Libraries/images/flecha.png", 16, 16, "*", function () {
                window.open("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
            }))
            ///
            .appendField("digitalRead pin")
            .appendField(new Blockly.FieldDropdown(function () {
                let pinNumbers = [];
                for (let i = 0; i <= 13; i++) {
                    pinNumbers.push([i.toString(), i.toString()]);
                }
                return pinNumbers;
            }), "PIN");
        this.setOutput(true, "Number");
        this.setColour(230);
        this.setTooltip("Lee el valor de un pin digital especificado, ya sea HIGH o LOW.");
        
    }
};

Blockly.JavaScript['arduino_digital_read'] = function (block) {
    var pin = block.getFieldValue('PIN');
    var code = 'digitalRead(' + pin + ')';
    return [code, Blockly.JavaScript.ORDER_ATOMIC];
};