Blockly.Blocks['serial_begin'] = {
  init: function() {
    this.svgGroup_.setAttribute('data-attribute', 'setup');
    this.svgGroup_.setAttribute('special-attribute', 'serial');
    this.appendDummyInput()
        .appendField("Inicializar puerto serial en frecuencia(")
        .appendField(new Blockly.FieldNumber(9600), "BAUDRATE")
        .appendField(");");
    this.getField("BAUDRATE").setMax(1000000);
    this.getField("BAUDRATE").setMin(-0);
    this.setPreviousStatement(true, 'setup');
    this.setNextStatement(true, 'setup');
    this.setColour('#bb7111');
    this.setTooltip("Inicializa la comunicación por puerto serial en baudios, usualmente en multiplos de 1200");
  },
  // Override the onchange handler to check for instances of this block
  onchange: function() {
    // Count the number of instances of this block in the workspace
    const instances = this.workspace.getBlocksByType('serial_begin');
    if (instances.length > 1) {
        // If there is more than one instance, destroy this block and alert the user
        alert("Solo se admite una instancia para inicializar la comunicación serial!");
        this.dispose();
    }
  }
};

Blockly.JavaScript['serial_begin'] = function(block) {
  var baudrate = block.getFieldValue('BAUDRATE');
  var code = 'Serial.begin(' + baudrate + ');\n';
  return code;
};