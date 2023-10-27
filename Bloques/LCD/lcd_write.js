Blockly.Blocks['LCDwrite'] = {
    init: function () {
        // Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
        this.svgGroup_.setAttribute('special-attribute', 'LCD');

        // Agregar entrada de valor para el texto a escribir en la pantalla
        this.appendValueInput("TEXT")
            .setCheck(["Number", "Text"])
            .appendField("lcd.write(");
        this.appendDummyInput()
            .appendField(")");
        // Configurar conexiones del bloque
        this.setPreviousStatement(true, "!arduino_case");
        this.setNextStatement(true, "!arduino_case");
        // Establecer el color del bloque
        this.setColour('#c50000');
        this.setTooltip("Escribe lo asignado en la pantalla LCD de forma literal");
    }
};
    
Blockly.JavaScript['LCDwrite'] = function (block) {
    // Obtiene el texto a escribir en la pantalla
    const text = Blockly.JavaScript.valueToCode(block, 'TEXT', Blockly.JavaScript.ORDER_ATOMIC);
    // Genera el código JavaScript para escribir el texto en la pantalla
    const code = `lcd.write(${text});\n`;
    
    // Retorna el código generado
    return code;
};