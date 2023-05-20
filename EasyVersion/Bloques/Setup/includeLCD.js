// Define the LCD pin connections
const rs = 12;
const rw = 11; // Not used, set to -1
const enable = 10;
const d0 = 5;
const d1 = 4;
const d2 = 3;
const d3 = 2;
const d4 = 9;
const d5 = 8;
const d6 = 7;
const d7 = 6;

Blockly.Blocks['includeLCD'] = {
  init: function() {
    // Add attributes
    this.svgGroup_.setAttribute('data-attribute', 'include');
    this.svgGroup_.setAttribute('special-attribute', 'UNO');

    this.appendDummyInput()
        .appendField("(arduino UNO) #incluir librerias para LCD");

    // Add fields for LCD pin connections
    this.appendDummyInput()
        .appendField("Pines: ")
        .appendField("RS:")
        .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "RS")
        .appendField("RW:")
        .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "RW")
        .appendField("ENABLE:")
        .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "ENABLE")
        .appendField("D0:")
        .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "D0")
        .appendField("D1:")
        .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "D1")
        .appendField("D2:")
        .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "D2")
        .appendField("D3:")
        .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "D3")
        .appendField("D4:")
        .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "D4")
        .appendField("D5:")
        .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "D5")
        .appendField("D6:")
        .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "D6")
        .appendField("D7:")
        .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "D7")


    // Set default values for the dropdowns
    this.setFieldValue(String(rs), "RS");
    this.setFieldValue(String(rw), "RW");
    this.setFieldValue(String(enable), "ENABLE");
    this.setFieldValue(String(d0), "D0");
    this.setFieldValue(String(d1), "D1");
    this.setFieldValue(String(d2), "D2");
    this.setFieldValue(String(d3), "D3");
    this.setFieldValue(String(d4), "D4");
    this.setFieldValue(String(d5), "D5");
    this.setFieldValue(String(d6), "D6");
    this.setFieldValue(String(d7), "D7");
    this.setPreviousStatement(true, "include");
    this.setNextStatement(true, "include");
    this.setColour('#a0600c');
    this.setTooltip('Incluye la libreria y define los pines para el uso de pantalla LCD');
    
  },
  /*
  // Custom validator function to restrict connections
  validate: function(otherConnectedBlock, newlyConnectedBlock) {
    if (newlyConnectedBlock && newlyConnectedBlock.getAttribute('data-attribute') !== 'include') {
      // Disallow connection if the newly connected block is not an 'include' block
      return false;
    }
    return true;
  }
  */
    // Override the onchange handler to check for instances of this block
    onchange: function() {
    // Count the number of instances of this block in the workspace
    const instances = this.workspace.getBlocksByType('includeLCD');
    if (instances.length > 1) {
        // If there is more than one instance, destroy this block and alert the user
        alert("Solo se admite una instancia para incluir el LCD!");
        this.dispose();
    }
  }
};

Blockly.JavaScript['includeLCD'] = function(block) {
  const fixedText = "#include <LiquidCrystal.h>";
  const rs = block.getFieldValue("RS");
  const rw = block.getFieldValue("RW");
  const enable = block.getFieldValue("ENABLE");
  const d0 = block.getFieldValue("D0");
  const d1 = block.getFieldValue("D1");
  const d2 = block.getFieldValue("D2");
  const d3 = block.getFieldValue("D3");
  const d4 = block.getFieldValue("D4");
  const d5 = block.getFieldValue("D5");
  const d6 = block.getFieldValue("D6");
  const d7 = block.getFieldValue("D7");

  // Generate the code for the LiquidCrystal library with the pin connections
  const code = `LiquidCrystal lcd(${rs}, ${rw}, ${enable}, ${d0}, ${d1}, ${d2}, ${d3}, ${d4}, ${d5}, ${d6}, ${d7});\n`;
  return fixedText + '\n' + code;
  };







  Blockly.Blocks['includeLCD_MEGA'] = {
    init: function() {
      // Add attributes
      this.svgGroup_.setAttribute('data-attribute', 'include');
      this.svgGroup_.setAttribute('special-attribute', 'MEGA');

      this.appendDummyInput()
          .appendField("(arduino MEGA) #incluir librerias para LCD");
  
      // Add fields for LCD pin connections
      this.appendDummyInput()
          .appendField("Pines: ")
          .appendField("RS:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "RS")
          .appendField("RW:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "RW")
          .appendField("ENABLE:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "ENABLE")
          .appendField("D0:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "D0")
          .appendField("D1:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "D1")
          .appendField("D2:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "D2")
          .appendField("D3:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "D3")
          .appendField("D4:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "D4")
          .appendField("D5:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "D5")
          .appendField("D6:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "D6")
          .appendField("D7:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "D7")
  
  
      // Set default values for the dropdowns
      this.setFieldValue(String(rs), "RS");
      this.setFieldValue(String(rw), "RW");
      this.setFieldValue(String(enable), "ENABLE");
      this.setFieldValue(String(d0), "D0");
      this.setFieldValue(String(d1), "D1");
      this.setFieldValue(String(d2), "D2");
      this.setFieldValue(String(d3), "D3");
      this.setFieldValue(String(d4), "D4");
      this.setFieldValue(String(d5), "D5");
      this.setFieldValue(String(d6), "D6");
      this.setFieldValue(String(d7), "D7");
      this.setPreviousStatement(true, "include");
      this.setNextStatement(true, "include");
      this.setColour('#a0600c');
      this.setTooltip('Incluye la libreria y define los pines para el uso de pantalla LCD');
      
    },
    /*
    // Custom validator function to restrict connections
    validate: function(otherConnectedBlock, newlyConnectedBlock) {
      if (newlyConnectedBlock && newlyConnectedBlock.getAttribute('data-attribute') !== 'include') {
        // Disallow connection if the newly connected block is not an 'include' block
        return false;
      }
      return true;
    }
    */
      // Override the onchange handler to check for instances of this block
      onchange: function() {
      // Count the number of instances of this block in the workspace
      const instances = this.workspace.getBlocksByType('includeLCD_MEGA');
      if (instances.length > 1) {
          // If there is more than one instance, destroy this block and alert the user
          alert("Solo se admite una instancia para incluir el LCD!");
          this.dispose();
      }
    }
  };
  
  Blockly.JavaScript['includeLCD_MEGA'] = function(block) {
    const fixedText = "#include <LiquidCrystal.h>";
    const rs = block.getFieldValue("RS");
    const rw = block.getFieldValue("RW");
    const enable = block.getFieldValue("ENABLE");
    const d0 = block.getFieldValue("D0");
    const d1 = block.getFieldValue("D1");
    const d2 = block.getFieldValue("D2");
    const d3 = block.getFieldValue("D3");
    const d4 = block.getFieldValue("D4");
    const d5 = block.getFieldValue("D5");
    const d6 = block.getFieldValue("D6");
    const d7 = block.getFieldValue("D7");
  
    // Generate the code for the LiquidCrystal library with the pin connections
    const code = `LiquidCrystal lcd(${rs}, ${rw}, ${enable}, ${d0}, ${d1}, ${d2}, ${d3}, ${d4}, ${d5}, ${d6}, ${d7});\n`;
    return fixedText + '\n' + code;
    };