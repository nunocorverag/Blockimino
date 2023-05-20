//define



Blockly.Blocks['define_list_UNO'] = {//////////
    init: function () {

        this.svgGroup_.setAttribute('data-attribute', 'parameters');
        this.svgGroup_.setAttribute('special-attribute', 'UNO');
        
        this.appendDummyInput()
            .setAlign(Blockly.ALIGN_RIGHT)
            .appendField("Seleccionar valor definido")//////////
            .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "DROPDOWN_LIST");//////////
        this.setOutput(true, ["define", "variable", "Number"]);
        this.setColour("#bb7111");
        this.setTooltip("Selecciona un valor previamente nombrado con #define");//////////
    }
};
Blockly.JavaScript['define_list_UNO'] = function (block) {//////////
    let dropdown_list = block.getFieldValue('DROPDOWN_LIST');

    return [dropdown_list, Blockly.JavaScript.ORDER_ATOMIC];
};



Blockly.Blocks['define_list_MEGA'] = {//////////
    init: function () {

        this.svgGroup_.setAttribute('data-attribute', 'parameters');
        this.svgGroup_.setAttribute('special-attribute', 'MEGA');

        this.appendDummyInput()
            .setAlign(Blockly.ALIGN_RIGHT)
            .appendField("Seleccionar valor definido")//////////
            .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "DROPDOWN_LIST");//////////
        this.setOutput(true, ["define", "variable", "Number"]);
        this.setColour("#bb7111");
        this.setTooltip("Selecciona un valor previamente nombrado con #define");//////////
    }
};
Blockly.JavaScript['define_list_MEGA'] = function (block) {//////////
    let dropdown_list = block.getFieldValue('DROPDOWN_LIST');

    return [dropdown_list, Blockly.JavaScript.ORDER_ATOMIC];
};








Blockly.Blocks['create_define_UNO'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'declarations');
        this.svgGroup_.setAttribute('special-attribute', 'UNO');

        this.appendDummyInput()
            .setAlign(Blockly.ALIGN_RIGHT)
            .appendField("(arduino UNO) #define") // Modificado a #define
            .appendField(new Blockly.FieldTextInput("NombreDefine", this.textInputValidator), "TEXT_INPUT")
            .appendField(" =")
            .appendField(new Blockly.FieldDropdown(function () {
                let optionsU = [];
                for (let i = 0; i <= 13; i++) {
                    optionsU.push([i.toString(), i.toString()]);
                }
                for (let i = 0; i <= 5; i++) {
                    optionsU.push(["A" + i.toString(), "A" + i.toString()]);
                }
                return optionsU;
            }), "PIN")
            .setAlign(Blockly.ALIGN_RIGHT);
        this.setColour("#a0600c");
        this.setTooltip("Es una manera de asignar un nombre a un número o valor en Arduino, similar a una variable.");

        this.setPreviousStatement(true, "include");
        this.setNextStatement(true, "include");
    },

    textInputValidator: function(newValue) {
        let sourceBlock = this.getSourceBlock();
        if (sourceBlock) {
          let oldValue = sourceBlock.getFieldValue('TEXT_INPUT');
          if (oldValue !== newValue) {
            setTimeout(updateDropdownLists, 0);
          }
        }
        // Accept only letters and numbers in the input value
        var regex = /^[a-zA-Z][a-zA-Z0-9]*$/;
        if (regex.test(newValue)) {
          return newValue;
        } else {
          return null;
        }
      }
};

Blockly.JavaScript['create_define_UNO'] = function (block) {
    var textInput = block.getFieldValue('TEXT_INPUT');
    var valueInput = block.getFieldValue('PIN');

    var code = '#define ' + textInput; // Modificado a #define
    if (valueInput) {
        code += ' ' + valueInput; // Eliminado el signo igual
    }
    code += '\n'; // Eliminado el punto y coma

    return code;
};


Blockly.Blocks['create_define_MEGA'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'declarations');
        this.svgGroup_.setAttribute('special-attribute', 'MEGA');

        this.appendDummyInput()
            .setAlign(Blockly.ALIGN_RIGHT)
            .appendField("(arduino MEGA) #define") // Modificado a #define
            .appendField(new Blockly.FieldTextInput("NombreDefine", this.textInputValidator), "TEXT_INPUT")
            .appendField(" =")
            .appendField(new Blockly.FieldDropdown(function () {
                let optionsM = [];
                for (let i = 0; i <= 53; i++) {
                    optionsM.push([i.toString(), i.toString()]);
                }
                for (let i = 0; i <= 15; i++) {
                    optionsM.push(["A" + i.toString(), "A" + i.toString()]);
                }
                return optionsM;
            }), "PIN")
            .setAlign(Blockly.ALIGN_RIGHT);
        this.setColour("#a0600c");
        this.setTooltip("Es una manera de asignar un nombre a un número o valor en Arduino, similar a una variable.");

        this.setPreviousStatement(true, "include");
        this.setNextStatement(true, "include");
    },

    textInputValidator: function(newValue) {
        let sourceBlock = this.getSourceBlock();
        if (sourceBlock) {
          let oldValue = sourceBlock.getFieldValue('TEXT_INPUT');
          if (oldValue !== newValue) {
            setTimeout(updateDropdownLists, 0);
          }
        }
        // Accept only letters and numbers in the input value
        var regex = /^[a-zA-Z][a-zA-Z0-9]*$/;
        if (regex.test(newValue)) {
          return newValue;
        } else {
          return null;
        }
      }
};

Blockly.JavaScript['create_define_MEGA'] = function (block) {
    var textInput = block.getFieldValue('TEXT_INPUT');
    var valueInput = block.getFieldValue('PIN');

    var code = '#define ' + textInput; // Modificado a #define
    if (valueInput) {
        code += ' ' + valueInput; // Eliminado el signo igual
    }
    code += '\n'; // Eliminado el punto y coma

    return code;
};