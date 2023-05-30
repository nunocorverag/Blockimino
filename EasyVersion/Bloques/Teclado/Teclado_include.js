// Define the Teclado pin connections
const rp1 = 3;
const rp2 = 2;
const rp3 = 1;
const rp4 = 0;
const cp1 = 7;
const cp2 = 6;
const cp3 = 5;
const cp4 = 4;

Blockly.Blocks['Teclado_include'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'include');
        this.svgGroup_.setAttribute('special-attribute', 'UNO');

        this.appendDummyInput()
            .appendField("(arduino UNO) #incluir librerias para Teclado Matricial");

        this.appendDummyInput()
          .appendField("Pines: ")
          .appendField("ROW 1:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "RP1")
          .appendField("2:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "RP2")
          .appendField("3:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "RP3")
          .appendField("4:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "RP4")
          .appendField("  COL 1:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "CP1")
          .appendField("2:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "CP2")
          .appendField("3:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "CP3")
          .appendField("4:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_UNO), "CP4")
        this.setFieldValue(String(rp1), "RP1");
        this.setFieldValue(String(rp2), "RP2");
        this.setFieldValue(String(rp3), "RP3");
        this.setFieldValue(String(rp4), "RP4");
        this.setFieldValue(String(cp1), "CP1");
        this.setFieldValue(String(cp2), "CP2");
        this.setFieldValue(String(cp3), "CP3");
        this.setFieldValue(String(cp4), "CP4");
        this.setPreviousStatement(true, "include");
        this.setNextStatement(true, "include");
        this.setColour('#a0600c');
        this.setTooltip('Incluye la libreria y define los pines para el uso de teclado matricial');
    },
    onchange: function() {
      // Count the number of instances of this block in the workspace
      const instances = this.workspace.getBlocksByType('Teclado_include');
      if (instances.length > 1) {
          // If there is more than one instance, destroy this block and alert the user
          alert("Solo se admite una instancia para incluir el Teclado!");
          this.dispose();
          window.includeCounter++;
      }
    }
};


Blockly.JavaScript['Teclado_include'] = function (block) {
    const rp1 = block.getFieldValue("RP1");
    const rp2 = block.getFieldValue("RP2");
    const rp3 = block.getFieldValue("RP3");
    const rp4 = block.getFieldValue("RP4");
    const cp1 = block.getFieldValue("CP1");
    const cp2 = block.getFieldValue("CP2");
    const cp3 = block.getFieldValue("CP3");
    const cp4 = block.getFieldValue("CP4");
    const fixedText = `
#include <Keypad.h>
const byte rows=4;
const byte cols=4;
char keys[rows][cols]=
{
{‘1’,’2’,’3’,’A’},
{‘4’,’5’,’6’,’B’},
{‘7’,’8’,’9’,’C’},
{‘*’,’0’,’#’,’D’}
};
byte rowPins[rows]={${rp1},${rp2},${rp3},${rp4}};
byte colPins[rows]={${cp1},${cp2},${cp3},${cp4}};
Keypad keypad=Keypad(makeKeymap(keys),rowPins,colPins,rows,cols);
//variable donde estará la tecla usada
char Key;\n
    `;
    return fixedText + '\n';
};










Blockly.Blocks['Teclado_include_MEGA'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'include');
        this.svgGroup_.setAttribute('special-attribute', 'MEGA');

        this.appendDummyInput()
            .appendField("(arduino MEGA) #incluir librerias para Teclado Matricial");

        this.appendDummyInput()
          .appendField("Pines: ")
          .appendField("ROW 1:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "RP1")
          .appendField("2:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "RP2")
          .appendField("3:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "RP3")
          .appendField("4:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "RP4")
          .appendField("  COL 1:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "CP1")
          .appendField("2:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "CP2")
          .appendField("3:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "CP3")
          .appendField("4:")
          .appendField(new Blockly.FieldDropdown(updateDropdownOptions_MEGA), "CP4")
        this.setFieldValue(String(rp1), "RP1");
        this.setFieldValue(String(rp2), "RP2");
        this.setFieldValue(String(rp3), "RP3");
        this.setFieldValue(String(rp4), "RP4");
        this.setFieldValue(String(cp1), "CP1");
        this.setFieldValue(String(cp2), "CP2");
        this.setFieldValue(String(cp3), "CP3");
        this.setFieldValue(String(cp4), "CP4");
        this.setPreviousStatement(true, "include");
        this.setNextStatement(true, "include");
        this.setColour('#a0600c');
        this.setTooltip('Incluye la libreria y define los pines para el uso de teclado matricial');
    },
    onchange: function() {
      // Count the number of instances of this block in the workspace
      const instances = this.workspace.getBlocksByType('Teclado_include_MEGA');
      if (instances.length > 1) {
          // If there is more than one instance, destroy this block and alert the user
          alert("Solo se admite una instancia para incluir el Teclado!");
          this.dispose();
          window.includeCounter++;
      }
    }
};


Blockly.JavaScript['Teclado_include_MEGA'] = function (block) {
    const rp1 = block.getFieldValue("RP1");
    const rp2 = block.getFieldValue("RP2");
    const rp3 = block.getFieldValue("RP3");
    const rp4 = block.getFieldValue("RP4");
    const cp1 = block.getFieldValue("CP1");
    const cp2 = block.getFieldValue("CP2");
    const cp3 = block.getFieldValue("CP3");
    const cp4 = block.getFieldValue("CP4");
    const fixedText = `
#include <Keypad.h>
const byte rows=4;
const byte cols=4;
char keys[rows][cols]=
{
{‘1’,’2’,’3’,’A’},
{‘4’,’5’,’6’,’B’},
{‘7’,’8’,’9’,’C’},
{‘*’,’0’,’#’,’D’}
};
byte rowPins[rows]={${rp1},${rp2},${rp3},${rp4}};
byte colPins[rows]={${cp1},${cp2},${cp3},${cp4}};
Keypad keypad=Keypad(makeKeymap(keys),rowPins,colPins,rows,cols);
//variable donde estará la tecla usada
char Key;\n
    `;
    return fixedText + '\n';
};
