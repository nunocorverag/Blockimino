Blockly.Blocks['simple_text'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');

        this.appendDummyInput()
            .appendField("Texto fijo:");
        this.setPreviousStatement(true, "Boolean");
        this.setNextStatement(true, "Boolean");
        this.setColour(120);
        
    }
};

Blockly.JavaScript['simple_text'] = function (block) {
    const fixedText = "Texto fijo";
    return fixedText + ';\n';
};
