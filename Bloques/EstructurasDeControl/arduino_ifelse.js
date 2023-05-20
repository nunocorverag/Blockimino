    // Inicializa el bloque 'arduino_if'
    Blockly.Blocks['arduino_ifelse'] = {
        // Función de inicialización del bloque
        init: function() {
        // Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'objects');
            // Configura el bloque con campos de texto/variable/número
            this.appendDummyInput()
                .appendField("if");
            // Agregar una entrada para la primera condición
            this.appendValueInput("CONDITION1")
                .setCheck(["Number", "Text", "Boolean", "bool"]);
            // Agregar un menú para el operador de comparación
            this.appendDummyInput()
                .appendField(new Blockly.FieldDropdown([["<", "<"], [">", ">"], ["<=", "<="], [">=", ">="], ["==", "=="], ["!=", "!="]]), "OPERATOR");
            // Agregar una entrada para la segunda condición
            this.appendValueInput("CONDITION2")
                .setCheck(["Number", "Text", "Boolean", "bool"]);
            // Añadir entrada para la condición verdadera del if
            this.appendStatementInput("DO")
                .setCheck('!arduino_case');
            // Añadir entrada para la condición falsa del if
            this.appendStatementInput("ELSE")
                .appendField("else")
                .setCheck('!arduino_case');
            // Configurar conexiones del bloque
            this.setPreviousStatement(true, '!arduino_case');
            this.setNextStatement(true, '!arduino_case');
            // Establecer el color del bloque
            this.setColour('#bbb123');
            this.setTooltip("Ejecutará el codigo dentro si se cumple la condición, de lo contrario ejecuta el else");
        }
        };


// Generar código JavaScript para el bloque 'arduino_if'
Blockly.JavaScript['arduino_ifelse'] = function(block) {
    // Obtiene las condiciones y el operador de comparación
    const condition1 = Blockly.JavaScript.valueToCode(block, 'CONDITION1', Blockly.JavaScript.ORDER_ATOMIC);
    const condition2 = Blockly.JavaScript.valueToCode(block, 'CONDITION2', Blockly.JavaScript.ORDER_ATOMIC);
    const operator = block.getFieldValue('OPERATOR');
    const operatorStr = operator === '&&' || operator === '||' ? ` ${operator} ` : ` ${operator} `;
    
    // Obtiene el contenido de la condición verdadera del if
    const trueCode = Blockly.JavaScript.statementToCode(block, 'DO');
    
    // Obtiene el contenido de la condición falsa del if
    const falseCode = Blockly.JavaScript.statementToCode(block, 'ELSE');
    
    // Genera el código JavaScript para el if con los valores obtenidos
    let code = `if (${condition1}${operatorStr}${condition2}) {\n`
        + trueCode
        + '}\n';
        
    // Si hay una condición falsa, agrega el código al if
    if (falseCode) {
      code += 'else {\n'
        + falseCode
        + '}\n';
    }
    
    // Retorna el código generado
    return code;
    };
    
