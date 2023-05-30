//BOOL2


/*
Blockly.Blocks['bool_list'] = {//////////
    init: function () {

        this.svgGroup_.setAttribute('data-attribute', 'parameters');

        this.appendDummyInput()
            .setAlign(Blockly.ALIGN_RIGHT)
            .appendField("variable bool")//////////
            .appendField(new Blockly.FieldDropdown(updateDropdownOptions_bool), "DROPDOWN_LIST");//////////
        this.setOutput(true, ["bool", "variable", "Text"]);
        this.setColour("#005300");
        this.setTooltip("Selecciona una variable bool");//////////
    }
};
Blockly.JavaScript['bool_list'] = function (block) {//////////
    let dropdown_list = block.getFieldValue('DROPDOWN_LIST');
   
    return [dropdown_list, Blockly.JavaScript.ORDER_ATOMIC];
};
*/


/*
Blockly.Blocks['bool_list'] = {
  init: function () {
    this.svgGroup_.setAttribute('data-attribute', 'parameters');
    this.setOutput(true, ["bool", "variable", "Text"]);
    this.setColour("#005300");
    this.setTooltip("Selecciona una variable bool");

    let dropdownField;

    if (this.getRootBlock().type === 'create_function') {
      dropdownField = new Blockly.FieldDropdown(updateDropdownOptions_Localbool);
    } else {
      dropdownField = new Blockly.FieldDropdown(updateDropdownOptions_bool);
    }

    this.appendDummyInput()
      .setAlign(Blockly.ALIGN_RIGHT)
      .appendField("variable bool")
      .appendField(dropdownField, "DROPDOWN_LIST");
  }
};
Blockly.JavaScript['bool_list'] = function (block) {//////////
  let dropdown_list = block.getFieldValue('DROPDOWN_LIST');
 
  return [dropdown_list, Blockly.JavaScript.ORDER_ATOMIC];
};
*/



Blockly.Blocks['bool_list'] = {
  init: function () {
    this.svgGroup_.setAttribute('data-attribute', 'parameters');

    this.appendDummyInput()
      .setAlign(Blockly.ALIGN_RIGHT)
      .appendField("variable bool")
      .appendField(new Blockly.FieldDropdown(updateDropdownOptions_bool), "DROPDOWN_LIST");
    
    this.setOutput(true, ["bool", "variable", "Text"]);
    this.setColour("#005300");
    this.setTooltip("Selecciona una variable bool");
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
Blockly.JavaScript['bool_list'] = function (block) {//////////
  let dropdown_list = block.getFieldValue('DROPDOWN_LIST');
 
  return [dropdown_list, Blockly.JavaScript.ORDER_ATOMIC];
};




Blockly.Blocks['create_bool'] = {//////////
  init: function () {
    
      this.svgGroup_.setAttribute('data-attribute', 'declarations');

      this.appendDummyInput()

          .setAlign(Blockly.ALIGN_RIGHT)
          .appendField("bool")//////////
          .appendField(new Blockly.FieldTextInput("NombreBool", this.textInputValidator), "TEXT_INPUT")
          .appendField("=");
      this.appendValueInput("VALUE_INPUT")
          .setCheck("bool")//////////

          .setAlign(Blockly.ALIGN_RIGHT);
      this.setColour("#005300");
      this.setTooltip("variable que permite almacenar un valor verdadero o falso");//////////

      

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

Blockly.JavaScript['create_bool'] = function (block) {//////////
    var textInput = block.getFieldValue('TEXT_INPUT');
    var valueInput = Blockly.JavaScript.valueToCode(block, 'VALUE_INPUT', Blockly.JavaScript.ORDER_ATOMIC);

    var code = 'bool ' + textInput;//////////
    if (valueInput) {
        code += ' = ' + valueInput;
    }
    code += ';\n';

    return code;
};