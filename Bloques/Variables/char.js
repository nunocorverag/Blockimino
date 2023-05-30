//CHAR3



Blockly.Blocks['char_list'] = {//////////
    init: function () {

        this.svgGroup_.setAttribute('data-attribute', 'parameters');

        this.appendDummyInput()
            .setAlign(Blockly.ALIGN_RIGHT)
            .appendField("variable char")//////////
            .appendField(new Blockly.FieldDropdown(updateDropdownOptions_char), "DROPDOWN_LIST");//////////
        this.setOutput(true, ["char", "variable", "Text"]);
        this.setColour("#008000");
        this.setTooltip("Selecciona una variable char");//////////
    },
    onchange: function (event) {
      const blockId = this.id;
      if (event.blockId !== blockId) {
        return; // Skip if the event is not for the current block
      }
    
      const dropdown = this.getField("DROPDOWN_LIST");
      if (dropdown) {
        const currentValue = dropdown.getValue();
        const availableOptions = dropdown.getOptions();
        const isCurrentOptionAvailable = availableOptions.some(option => option[1] === currentValue);
    
        if (!isCurrentOptionAvailable && availableOptions.length > 0) {
          const firstOptionValue = availableOptions[0][1];
          dropdown.setValue(firstOptionValue);
        }
      }
    }
};
Blockly.JavaScript['char_list'] = function (block) {//////////
    let dropdown_list = block.getFieldValue('DROPDOWN_LIST');

    return [dropdown_list, Blockly.JavaScript.ORDER_ATOMIC];
};


Blockly.Blocks['create_char'] = {//////////
    init: function () {

        this.svgGroup_.setAttribute('data-attribute', 'declarations');

        this.appendDummyInput()

            .setAlign(Blockly.ALIGN_RIGHT)
            .appendField("char")//////////
            .appendField(new Blockly.FieldTextInput("NombreChar", this.textInputValidator), "TEXT_INPUT")
            .appendField("=");
        this.appendValueInput("VALUE_INPUT")
            .setCheck("char")//////////

            .setAlign(Blockly.ALIGN_RIGHT);
        this.setColour("#008000");
        this.setTooltip("variable que permite almacenar un solo caracter");//////////



        this.setPreviousStatement(true, "create_var");
        this.setNextStatement(true, "create_var");

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
      },
    
    onchange: function () {
    setTimeout(updateDropdownLists, 0);
    }
};


Blockly.JavaScript['create_char'] = function (block) {//////////
    var textInput = block.getFieldValue('TEXT_INPUT');
    var valueInput = Blockly.JavaScript.valueToCode(block, 'VALUE_INPUT', Blockly.JavaScript.ORDER_ATOMIC);

    var code = 'char ' + textInput;//////////
    if (valueInput) {
        code += ' = ' + valueInput;
    }
    code += ';\n';

    return code;
};