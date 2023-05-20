Blockly.Blocks['HC04_begin'] = {
  init: function() {
    // Add attributes
    this.svgGroup_.setAttribute('data-attribute', 'setup');

    this.appendDummyInput()
        .appendField("Configurar Sensor de distancia HC-04");

    // Add fields for LCD pin connections
    this.appendDummyInput()
        .appendField("TriggerOUTPUT  EchoINPUT  TriggerLOW")

    this.setPreviousStatement(true, "setup");
    this.setNextStatement(true, "setup");
    this.setColour('#bb7111');
    this.setTooltip('Configurar lo necesario para el HC04');
    
  },

    // Override the onchange handler to check for instances of this block
    onchange: function() {
    // Count the number of instances of this block in the workspace
    const instances = this.workspace.getBlocksByType('HC04_begin');
    if (instances.length > 1) {
        // If there is more than one instance, destroy this block and alert the user
        alert("Only one instance of HC04_begin block is allowed.");
        this.dispose();
    }
  }
};

Blockly.JavaScript['HC04_begin'] = function(block) {
  const fixedText = "//Sensor de distancia HC-04\n";
  // Generate the code for the LiquidCrystal library with the pin connections
  const code = `pinMode(Trigger, OUTPUT);\npinMode(Echo, INPUT);\ndigitalWrite(Trigger, LOW);\n`;
  return fixedText + '\n' + code;
  };
