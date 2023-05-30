const trig = 2;
const ech = 3;

Blockly.Blocks['HC04_include'] = {
    init: function() {
      this.svgGroup_.setAttribute('data-attribute', 'include');

      this.appendDummyInput()
          .appendField("(arduino UNO) Definir pines de ultrasonido HC-04")
      this.appendDummyInput()
          .appendField("Trigger pin:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "TRIGGER");
      this.appendDummyInput()
          .appendField("Echo pin:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "ECHO")

      this.setFieldValue(String(trig), "TRIGGER");
      this.setFieldValue(String(ech), "ECHO");
      this.setInputsInline(false);
      //this.setColour('#2a2a2a');
      this.setColour('#a0600c');
      this.setPreviousStatement(true, "include");
      this.setNextStatement(true, "include");
      this.setTooltip('Declarar los pines para el sensor HC04');
      this.setHelpUrl('');
    },
    onchange: function() {
      // Count the number of instances of this block in the workspace
      const instances = this.workspace.getBlocksByType('HC04_include');
      if (instances.length > 1) {
          // If there is more than one instance, destroy this block and alert the user
          alert("Only one instance of HC04_include block is allowed.");
          this.dispose();
          window.includeCounter++;
      }
    }
  };

  Blockly.JavaScript['HC04_include'] = function(block) {
    var dropdown_trigger_pin = block.getFieldValue('TRIGGER');
    var dropdown_echo_pin = block.getFieldValue('ECHO');
    var code = 'const int Trigger = ' + dropdown_trigger_pin + ';\n' + 'const int Echo = ' + dropdown_echo_pin + ';\n';
    code = code + 'long t; //tiempo que demora en llegar el eco\nlong d; //distancia en centímetros'
    
    return code;
  };


  Blockly.Blocks['HC04_include_MEGA'] = {
    init: function() {
      this.svgGroup_.setAttribute('data-attribute', 'include');

      this.appendDummyInput()
          .appendField("(arduino MEGA) Definir pines de ultrasonido HC-04")
      this.appendDummyInput()
          .appendField("Trigger pin:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "TRIGGER");
      this.appendDummyInput()
          .appendField("Echo pin:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "ECHO")

      this.setFieldValue(String(trig), "TRIGGER");
      this.setFieldValue(String(ech), "ECHO");
      this.setInputsInline(false);
      //this.setColour('#2a2a2a');
      this.setColour('#a0600c');
      this.setPreviousStatement(true, "include");
      this.setNextStatement(true, "include");
      this.setTooltip('Declarar los pines y variables para el sensor HC04');
    },
    onchange: function() {
      // Count the number of instances of this block in the workspace
      const instances = this.workspace.getBlocksByType('HC04_include_MEGA');
      if (instances.length > 1) {
          // If there is more than one instance, destroy this block and alert the user
          alert("Only one instance of HC04_include block is allowed.");
          this.dispose();
          window.includeCounter++;
      }
    }
  };

  Blockly.JavaScript['HC04_include_MEGA'] = function(block) {
    var dropdown_trigger_pin = block.getFieldValue('TRIGGER');
    var dropdown_echo_pin = block.getFieldValue('ECHO');
    var code = 'const int Trigger = ' + dropdown_trigger_pin + ';\n' + 'const int Echo = ' + dropdown_echo_pin + ';\n';
    code = code + 'long t; //tiempo que demora en llegar el eco\nlong d; //distancia en centímetros'
    
    return code;
  };