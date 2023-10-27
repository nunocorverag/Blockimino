Blockly.Blocks['LCDsetCursor'] = {
    init: function() {
    // Agregar atributos
    this.svgGroup_.setAttribute('data-attribute', 'objects');
    this.svgGroup_.setAttribute('special-attribute', 'LCD');

    // Agregar los campos de entrada
    this.appendValueInput("COL")
        .setCheck(["int", "intval", "short", "shortval", "long", "longval"])
        .appendField("lcd establecer cursor");
    this.appendValueInput("ROW")
        .setCheck(["int", "intval", "short", "shortval", "long", "longval"])
        .appendField(",");
    this.appendDummyInput()
    // Configurar conexiones del bloque
    this.setPreviousStatement(true, "!arduino_case");
    this.setNextStatement(true, "!arduino_case");
    // Establecer el color del bloque
    this.setColour('#c50000');
    // Establecer la descripción del bloque
    this.setTooltip('Establece una nueva pocision para el cursor');
    }
    };
    
Blockly.JavaScript['LCDsetCursor'] = function (block) {
    const col = Blockly.JavaScript.valueToCode(block, 'COL', Blockly.JavaScript.ORDER_ATOMIC) || 0;
    const row = Blockly.JavaScript.valueToCode(block, 'ROW', Blockly.JavaScript.ORDER_ATOMIC) || 0;
    const fixedText = "lcd.setCursor(" + col + "," + row + ");";
    const code = fixedText + '\n';
    return code;
};