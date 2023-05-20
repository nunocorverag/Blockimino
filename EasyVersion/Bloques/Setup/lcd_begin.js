Blockly.Blocks['LCDbegin'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'setup');
        this.svgGroup_.setAttribute('special-attribute', 'LCD');
        this.appendDummyInput()
            .appendField("lcd iniciar(")
            .appendField(new Blockly.FieldDropdown([["16x2", "16, 2"], ["20x4", "20, 4"], ["8x2", "8, 2"], ["12x2", "12, 2"]]), "LCD_TYPE")
            .appendField(")");
        this.setPreviousStatement(true, "setup");
        this.setNextStatement(true, "setup");
        this.setColour('#bb7111');
        this.setTooltip('Inicializa la pantalla segun el tipo');
    },
    // Override the onchange handler to check for instances of this block
    onchange: function() {
        // Count the number of instances of this block in the workspace
        const instances = this.workspace.getBlocksByType('LCDbegin');
        if (instances.length > 1) {
            // If there is more than one instance, destroy this block and alert the user
            alert("Solo se admite una instancia para inicializar el LCD!");
            this.dispose();
        }
    }
};
    
Blockly.JavaScript['LCDbegin'] = function (block) {
    const lcdType = block.getFieldValue("LCD_TYPE");
    const [columns, rows] = lcdType.split(", ");
    const code = `lcd.begin(${columns}, ${rows});\n`;
    return code;
};