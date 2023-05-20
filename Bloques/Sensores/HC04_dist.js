Blockly.Blocks['HC04_dist'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameter');
        this.svgGroup_.setAttribute('special-attribute', 'HC04');
        this.svgGroup_.setAttribute('value-attribute', 'constant');

        this.appendDummyInput()
            .appendField("Valor de distancia HC04")
        this.setOutput(true, ["int", "Number"]);
        this.setColour("#008000");
        this.setTooltip("Toma la variable de distancia previamente creada");
    }
};

Blockly.JavaScript['HC04_dist'] = function (block) {
    const fixedText = "d";
    return [fixedText, Blockly.JavaScript.ORDER_ATOMIC];
};