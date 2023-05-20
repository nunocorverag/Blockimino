// Define the HC04 pin connections
const sensorLuz = 0;
const valorLuz  = 1;

Blockly.Blocks['LDR_include'] = {
  init: function() {
    // Add attributes
    this.svgGroup_.setAttribute('data-attribute', 'declarations');
    this.svgGroup_.setAttribute('special-attribute', 'UNO');

    this.appendDummyInput()
        .appendField("(arduino UNO) Definir pines de luminosidad LDR");

    // Add fields for LCD pin connections
    this.appendDummyInput()
        .appendField("sensorLuz:")
        .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "sensorLuz")
        .appendField("valorLuz:")
        .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "valorLuz")
    this.setFieldValue(String(sensorLuz), "sensorLuz");
    this.setFieldValue(String(valorLuz), "valorLuz");
    this.setPreviousStatement(true, "include");
    this.setNextStatement(true, "include");
    this.setColour('#a0600c');
    this.setTooltip('Declarar los pines y variables para el sensor LDR');
  },

    // Override the onchange handler to check for instances of this block
    onchange: function() {
    // Count the number of instances of this block in the workspace
    const instances = this.workspace.getBlocksByType('LDR_include');
    if (instances.length > 1) {
        // If there is more than one instance, destroy this block and alert the user
        alert("LDR solo se debe incluir una sola vez!");
        this.dispose();
    }
  }
};

Blockly.JavaScript['LDR_include'] = function(block) {
  const fixedText = "//Sensor de luminosidad LDR";
  const SL = (block.getFieldValue("sensorLuz"));
  const VA = (block.getFieldValue("valorLuz"));

  // Generate the code for the LiquidCrystal library with the pin connections
  const code = `#define sensorLuz ${SL}\nint valorLuz = ${VA};\n\n`;
  return fixedText + '\n' + code;
  };






  Blockly.Blocks['LDR_include_MEGA'] = {
    init: function() {
      // Add attributes
      this.svgGroup_.setAttribute('data-attribute', 'declarations');
      this.svgGroup_.setAttribute('special-attribute', 'MEGA');
  
      this.appendDummyInput()
          .appendField("(arduino MEGA) Definir pines de luminosidad LDR");
  
      // Add fields for LCD pin connections
      this.appendDummyInput()
          .appendField("sensorLuz:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "sensorLuz")
          .appendField("valorLuz:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "valorLuz")
      this.setFieldValue(String(sensorLuz), "sensorLuz");
      this.setFieldValue(String(valorLuz), "valorLuz");
      this.setPreviousStatement(true, "include");
      this.setNextStatement(true, "include");
      this.setColour('#a0600c');
    },
  
      // Override the onchange handler to check for instances of this block
      onchange: function() {
      // Count the number of instances of this block in the workspace
      const instances = this.workspace.getBlocksByType('LDR_include_MEGA');
      if (instances.length > 1) {
          // If there is more than one instance, destroy this block and alert the user
          alert("LDR solo se debe incluir una sola vez!");
          this.dispose();
      }
    }
  };
  
  Blockly.JavaScript['LDR_include_MEGA'] = function(block) {
    const fixedText = "//Sensor de luminosidad LDR";
    const SL = (block.getFieldValue("sensorLuz"));
    const VA = (block.getFieldValue("valorLuz"));
  
    // Generate the code for the LiquidCrystal library with the pin connections
    const code = `#define sensorLuz ${SL}\nint valorLuz = ${VA};\n\n`;
    return fixedText + '\n' + code;
    };