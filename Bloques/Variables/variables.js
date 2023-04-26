//Event Listeners de creacion y eliminacion de bloques
function updateDropdownLists() {
    let allBlocks = Blockly.mainWorkspace.getAllBlocks();
    textInputs = [];

    allBlocks.forEach(function (block) {
        if (block.type === 'create_var') {
            let text_input = block.getFieldValue('TEXT_INPUT');//bloques que pueden a�adir palabras a la lista
            textInputs.push(text_input);
        }

        if (block.type === 'int_var') {
            let text_input = block.getFieldValue('TEXT_INPUT');
            textInputs.push(text_input);
        }
    });

    allBlocks.forEach(function (block) {
        if (block.type === 'var_list') {
            let dropdownField = block.getField('DROPDOWN_LIST');
            if (dropdownField) {
                dropdownField.setOptions(updateDropdownOptions());
            }
        }
    });
}

Blockly.mainWorkspace.addChangeListener(function (event) {
    if (event.type === Blockly.Events.BLOCK_CREATE || event.type === Blockly.Events.BLOCK_DELETE) {
        updateDropdownLists();
    }
});

let textInputs = [];

function updateDropdownOptions() {
    let options = textInputs.map(text => [text, text]);
    if (options.length === 0) {
        options.push(['Ninguna', '']);
    }
    return options;
}

//////////////////Crear variables
Blockly.Blocks['create_var'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'declarations');

        this.appendDummyInput()
            .setAlign(Blockly.ALIGN_RIGHT)
            .appendField(new Blockly.FieldTextInput("NombreString", this.textInputValidator), "TEXT_INPUT");
        this.setColour(230);
        this.setTooltip("Introduce una palabra");
    },

    textInputValidator: function (newValue) {
        let sourceBlock = this.getSourceBlock();
        if (sourceBlock) {
            let oldValue = sourceBlock.getFieldValue('TEXT_INPUT');
            if (oldValue !== newValue) {
                setTimeout(updateDropdownLists, 0);
            }
        }
        return newValue;
    }
};
Blockly.JavaScript['create_var'] = function (block) {
    let text_input = block.getFieldValue('TEXT_INPUT');
    textInputs.push(text_input);
    const fixedText = text_input;
    return "string " + fixedText + ';\n';
};



///////////lista de variables
Blockly.Blocks['var_list'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'parameters');

        this.appendDummyInput()
            .setAlign(Blockly.ALIGN_RIGHT)
            .appendField(new Blockly.FieldDropdown(updateDropdownOptions), "DROPDOWN_LIST");
        this.setOutput(true, null);
        this.setColour(230);
        this.setTooltip("Selecciona una palabra");
    }
};
Blockly.JavaScript['var_list'] = function (block) {
    let dropdown_list = block.getFieldValue('DROPDOWN_LIST');
    //return [JSON.stringify(dropdown_list), Blockly.JavaScript.ORDER_ATOMIC];
    return [dropdown_list, Blockly.JavaScript.ORDER_ATOMIC];
};







///////////////crear variables tipo entero
Blockly.Blocks['int_var'] = {
    init: function () {
        //Agregar atributos
        this.svgGroup_.setAttribute('data-attribute', 'declarations');
        
        this.appendDummyInput()
            ///Añadir imagen con link
            .appendField(new Blockly.FieldImage("Libraries/images/flecha.png", 16, 16, "*", function () {
                window.open("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
            }))
            ///
            .setAlign(Blockly.ALIGN_RIGHT)
            .appendField("int")
            .appendField(new Blockly.FieldTextInput("NombreString", this.textInputValidator), "TEXT_INPUT")
            .appendField("=");
        this.appendValueInput("VALUE_INPUT")
            .setCheck(null)
            .setAlign(Blockly.ALIGN_RIGHT);
        this.setColour(230);
        this.setTooltip("Introduce una palabra");

        /*
        // Permitir conexiones arriba abajo
        this.setPreviousStatement(true);
        this.setNextStatement(true);
        */
    },

    textInputValidator: function (newValue) {
        let sourceBlock = this.getSourceBlock();
        if (sourceBlock) {
            let oldValue = sourceBlock.getFieldValue('TEXT_INPUT');
            if (oldValue !== newValue) {
                setTimeout(updateDropdownLists, 0);
            }
        }
        // Aceptar solo letras y n�meros en el valor de entrada
        var regex = /^[a-zA-Z0-9]+$/;
        if (regex.test(newValue)) {
            return newValue;
        } else {
            return null;
        }
    }
};////////////////eeeeeeeeeee APOCOSIPA
Blockly.JavaScript['int_var'] = function (block) {
    var textInput = block.getFieldValue('TEXT_INPUT');
    var valueInput = Blockly.JavaScript.valueToCode(block, 'VALUE_INPUT', Blockly.JavaScript.ORDER_ATOMIC);

    var code = 'int ' + textInput;
    if (valueInput) {
        code += ' = ' + valueInput;
    }
    code += ';\n';

    return code;
};


