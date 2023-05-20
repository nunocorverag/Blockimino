Blockly.Blocks['function_list_value'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'parameters');

        this.appendDummyInput()
            .setAlign(Blockly.ALIGN_RIGHT)
            .appendField(new Blockly.FieldDropdown(updateDropdownOptions_function), "DROPDOWN_LIST")
            .appendField("();");

        this.setOutput(true, ["funcion", "Number"]); // Conexi�n de salida de valor
        this.setColour("#494949");
        this.setTooltip("Ejecuta una funcion, si tiene return sera el valor que este bloque recibe");
    }
};

Blockly.JavaScript['function_list_value'] = function (block) {
    let dropdown_list = block.getFieldValue('DROPDOWN_LIST');
   
    return [dropdown_list, Blockly.JavaScript.ORDER_ATOMIC];
};


//conexiones a otros bloques
Blockly.Blocks['function_list'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'objects');

        this.appendDummyInput()
            .setAlign(Blockly.ALIGN_RIGHT)
            .appendField(new Blockly.FieldDropdown(updateDropdownOptions_function), "DROPDOWN_LIST")
            .appendField("();"); // Agrega los par�ntesis y el punto y coma en el campo de texto del bloque

        this.setOutput(false);
        this.setColour("#494949");

        this.setPreviousStatement(true, "!arduino_case"); // Permite conectar el bloque por arriba
        this.setNextStatement(true, "!arduino_case"); // Permite conectar el bloque por abajo
        this.setTooltip("Ejecuta una funcion, si tiene return sera el valor que este bloque recibe");
    }
};

Blockly.JavaScript['function_list'] = function (block) {
    let dropdown_list = block.getFieldValue('DROPDOWN_LIST');

    // Agrega par�ntesis y punto y coma a la llamada de la funci�n
    var code = dropdown_list + '();\n';
    return code;
};



Blockly.Blocks['create_function'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'functions');

        this.appendDummyInput()
            .setAlign(Blockly.ALIGN_RIGHT)
            .appendField(new Blockly.FieldDropdown([["int", "int"], ["float", "float"], ["double", "double"], ["long", "long"], ["short", "short"], ["bool", "bool"]]), "FUNC_TYPE")
            .appendField("funcion")
            .appendField(new Blockly.FieldTextInput("NombreFuncion", this.textInputValidator), "TEXT_INPUT");

        this.appendStatementInput("STATEMENTS")
            .setCheck("!arduino_case")
            .setAlign(Blockly.ALIGN_RIGHT);
        this.setColour("#494949");
        this.setTooltip("Variable que permite crear una funcion con caracteres globales");

        this.appendValueInput("RETURN")
            .setCheck(["Number", "bool"])
            .setAlign(Blockly.ALIGN_RIGHT)
            .appendField("retornar");

        this.setPreviousStatement(true, "funcion");
        this.setNextStatement(true, "funcion");
        this.setTooltip("Crea una función de cierto tipo, idealmente para poder retornar un valor de dicho tipo");
    },

    textInputValidator: function(newValue) {
        let sourceBlock = this.getSourceBlock();
        if (sourceBlock) {
          let oldValue = sourceBlock.getFieldValue('TEXT_INPUT');
          if (oldValue !== newValue) {
            setTimeout(updateDropdownLists, 0);
          }
        }
        // Accept only letters and numbers in the input value
        var regex = /^[a-zA-Z][a-zA-Z0-9]*$/;
        if (regex.test(newValue)) {
          return newValue;
        } else {
          return null;
        }
      }
};

Blockly.JavaScript['create_function'] = function (block) {
    var funcType = block.getFieldValue('FUNC_TYPE');
    var textInput = block.getFieldValue('TEXT_INPUT');
    var statements = Blockly.JavaScript.statementToCode(block, 'STATEMENTS');
    var valueInput = Blockly.JavaScript.valueToCode(block, 'RETURN', Blockly.JavaScript.ORDER_ATOMIC);

    var code = funcType + ' ' + textInput + '() {\n';
    code += statements;
    
    if (valueInput) {
        code += 'return ' + valueInput + ';\n';
    }

    code += '}\n';

    return code;
};



//---------------- VOID



Blockly.Blocks['create_void_function'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'functions');

        this.appendDummyInput()
            .setAlign(Blockly.ALIGN_RIGHT)
            .appendField("void funcion")
            .appendField(new Blockly.FieldTextInput("NombreFuncion", this.textInputValidator), "TEXT_INPUT");
        this.appendStatementInput("STATEMENTS")
            .setCheck("!arduino_case")
            .setAlign(Blockly.ALIGN_RIGHT);
        this.setColour("#494949");
        this.setTooltip("Variable que permite crear una funcion que no retorna nada");
        this.setPreviousStatement(true, "funcion");
        this.setNextStatement(true, "funcion");
        this.setTooltip("Crea una función void, es decir, no retorna nada");
    },

    textInputValidator: function(newValue) {
        let sourceBlock = this.getSourceBlock();
        if (sourceBlock) {
          let oldValue = sourceBlock.getFieldValue('TEXT_INPUT');
          if (oldValue !== newValue) {
            setTimeout(updateDropdownLists, 0);
          }
        }
        // Accept only letters and numbers in the input value
        var regex = /^[a-zA-Z][a-zA-Z0-9]*$/;
        if (regex.test(newValue)) {
          return newValue;
        } else {
          return null;
        }
      }
};

Blockly.JavaScript['create_void_function'] = function (block) {
    var textInput = block.getFieldValue('TEXT_INPUT');
    var statements = Blockly.JavaScript.statementToCode(block, 'STATEMENTS');

    var code = 'void ' + textInput + '() {\n';
    code += statements;
    code += '}\n';

    return code;
};
