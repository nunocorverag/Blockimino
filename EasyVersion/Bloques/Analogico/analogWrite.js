Blockly.Blocks['arduino_analog_write'] = {
    init: function () {
      this.svgGroup_.setAttribute('data-attribute', 'objects');
      this.svgGroup_.setAttribute('special-attribute', 'UNO');
      
      this.appendDummyInput()
        .appendField("(arduino UNO) Escribir valor en pin analogico(")
        .appendField(new Blockly.FieldDropdown([
          ["3", "3"],
          ["5", "5"],
          ["6", "6"],
          ["9", "9"],
          ["10", "10"],
          ["11", "11"]
        ]), "PIN")
        .appendField(", ");
      this.appendDummyInput()
        .appendField(new Blockly.FieldNumber(255, 0, 255), "VALUE");
      this.appendDummyInput()
        .appendField(")");
      this.setInputsInline(true);
      this.setPreviousStatement(true, "!arduino_case");
      this.setNextStatement(true, "!arduino_case");
      this.setColour("#5c00a3");
      this.setTooltip("Establece un valor PWM en un pin de salida analógica, donde 0 es nada, 255 es la maxima cantidad de voltaje y 127 es alreadedor de la mitad");
    }
  };

Blockly.JavaScript['arduino_analog_write'] = function (block) {
    var pin = block.getFieldValue('PIN');
    let value = block.getFieldValue('VALUE');
    var code = 'analogWrite(' + pin + ', ' + value + ');\n';
    return code;
};


Blockly.Blocks['MEGA_arduino_analog_write'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'MEGA');

        this.appendDummyInput()
            .appendField("(arduino MEGA) Escribir valor en pin analogico(")
            .appendField(new Blockly.FieldDropdown([
                ["2", "2"],
                ["3", "3"],
                ["4", "4"],
                ["5", "5"],
                ["6", "6"],
                ["7", "7"],
                ["8", "8"],
                ["9", "9"],
                ["10", "10"],
                ["11", "11"],
                ["12", "12"],
                ["13", "13"],
                ["44", "44"],
                ["45", "45"],
                ["46", "46"],
                ["47", "47"],
                ["48", "48"],
                ["49", "49"],
                ["50", "50"],
                ["51", "51"],
                ["52", "52"],
                ["53", "53"]
            ]), "PIN")
        .appendField(", ");
        this.appendDummyInput()
            .appendField(new Blockly.FieldNumber(255, 0, 255), "VALUE");
        this.appendDummyInput()
            .appendField(")");
        this.setInputsInline(true);
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        this.setColour("#5c00a3");
        this.setTooltip("Establece un valor PWM en un pin de salida analógica, donde 0 es nada, 255 es la maxima cantidad de voltaje y 127 es alreadedor de la mitad");
    }
};

Blockly.JavaScript['MEGA_arduino_analog_write'] = function (block) {
    var pin = block.getFieldValue('PIN');
    let value = block.getFieldValue('VALUE');
    var code = 'analogWrite(' + pin + ', ' + value + ');\n';
    return code;
};
