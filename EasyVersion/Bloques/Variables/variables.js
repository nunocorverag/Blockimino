//Event Listeners de creacion y eliminacion de bloques
function updateDropdownLists() {
    let allBlocks = Blockly.mainWorkspace.getAllBlocks();
    textInputs = [];
    bool_var_array = [];//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    char_var_array = [];
    double_var_array = [];
    float_var_array = [];
    int_var_array = [];
    long_var_array = [];
    short_var_array = [];
    string_var_array = [];
    function_array = [];
    define_array_UNO = [];
    define_array_MEGA = [];

    allBlocks.forEach(function (block) {
        if (block.type === 'create_var') {
            let text_input = block.getFieldValue('TEXT_INPUT');//bloques que pueden a?adir palabras a la lista
            textInputs.push(text_input);
        }

        if (block.type === 'int_var') {
            let text_input = block.getFieldValue('TEXT_INPUT');
            textInputs.push(text_input);
        }

        if (block.type === 'create_bool') {
            let text_input = block.getFieldValue('TEXT_INPUT');
            bool_var_array.push(text_input);
        }//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if (block.type === 'create_char') {
            let text_input = block.getFieldValue('TEXT_INPUT');
            char_var_array.push(text_input);
        }
        if (block.type === 'create_double') {
            let text_input = block.getFieldValue('TEXT_INPUT');
            double_var_array.push(text_input);
        }
        if (block.type === 'create_float') {
            let text_input = block.getFieldValue('TEXT_INPUT');
            float_var_array.push(text_input);
        }
        if (block.type === 'create_int') {
            let text_input = block.getFieldValue('TEXT_INPUT');
            int_var_array.push(text_input);
        }
        if (block.type === 'create_long') {
            let text_input = block.getFieldValue('TEXT_INPUT');
            long_var_array.push(text_input);
        }
        if (block.type === 'create_short') {
            let text_input = block.getFieldValue('TEXT_INPUT');
            short_var_array.push(text_input);
        }
        if (block.type === 'create_string') {
            let text_input = block.getFieldValue('TEXT_INPUT');
            string_var_array.push(text_input);
        }
        if (block.type === 'create_function') {
            let text_input = block.getFieldValue('TEXT_INPUT');
            function_array.push(text_input);
        }
        if (block.type === 'create_void_function') {
            let text_input = block.getFieldValue('TEXT_INPUT');
            function_array.push(text_input);
        }
        if (block.type === 'create_define_UNO') {
            let text_input = block.getFieldValue('TEXT_INPUT');
            define_array_UNO.push(text_input);
        }
        if (block.type === 'create_define_MEGA') {
            let text_input = block.getFieldValue('TEXT_INPUT');
            define_array_MEGA.push(text_input);
        }
    });

    allBlocks.forEach(function (block) {
        if (block.type === 'var_list') {
            let dropdownField = block.getField('DROPDOWN_LIST');
            if (dropdownField) {
                dropdownField.setOptions(updateDropdownOptions());
            }
        }

        if (block.type === 'bool_list') {////////////////////////////////////////////////////////
            let dropdownField = block.getField('DROPDOWN_LIST');
            if (dropdownField) {
                dropdownField.setOptions(updateDropdownOptions_bool());//////////////////////////////////////////
            }
        }//////////////////////////////////////////////////////////////////////////////////////////////////

        if (block.type === 'char_list') {
            let dropdownField = block.getField('DROPDOWN_LIST');
            if (dropdownField) {
                dropdownField.setOptions(updateDropdownOptions_char());
            }
        }
        if (block.type === 'double_list') {
            let dropdownField = block.getField('DROPDOWN_LIST');
            if (dropdownField) {
                dropdownField.setOptions(updateDropdownOptions_double());
            }
        }
        if (block.type === 'float_list') {
            let dropdownField = block.getField('DROPDOWN_LIST');
            if (dropdownField) {
                dropdownField.setOptions(updateDropdownOptions_float());
            }
        }
        if (block.type === 'int_list') {
            let dropdownField = block.getField('DROPDOWN_LIST');
            if (dropdownField) {
                dropdownField.setOptions(updateDropdownOptions_int());
            }
        }
        if (block.type === 'long_list') {
            let dropdownField = block.getField('DROPDOWN_LIST');
            if (dropdownField) {
                dropdownField.setOptions(updateDropdownOptions_long());
            }
        }
        if (block.type === 'short_list') {
            let dropdownField = block.getField('DROPDOWN_LIST');
            if (dropdownField) {
                dropdownField.setOptions(updateDropdownOptions_short());
            }
        }
        if (block.type === 'string_list') {
            let dropdownField = block.getField('DROPDOWN_LIST');
            if (dropdownField) {
                dropdownField.setOptions(updateDropdownOptions_string());
            }
        }
        if (block.type === 'function_list') {
            let dropdownField = block.getField('DROPDOWN_LIST');
            if (dropdownField) {
                dropdownField.setOptions(updateDropdownOptions_function());
            }
        }
        if (block.type === 'function_list_value') {
            let dropdownField = block.getField('DROPDOWN_LIST');
            if (dropdownField) {
                dropdownField.setOptions(updateDropdownOptions_function());
            }
        }
        if (block.type === 'define_list_UNO') {
            let dropdownField = block.getField('DROPDOWN_LIST');
            if (dropdownField) {
                dropdownField.setOptions(updateDropdownOptions_UNO());
            }
        }
        if (block.type === 'define_list_MEGA') {
            let dropdownField = block.getField('DROPDOWN_LIST');
            if (dropdownField) {
                dropdownField.setOptions(updateDropdownOptions_MEGA());
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

let bool_var_array = [];
let char_var_array = [];
let double_var_array = [];
let float_var_array = [];
let int_var_array = [];
let long_var_array = [];
let short_var_array = [];
let string_var_array = [];
let function_array = [];
let define_array_UNO = [];
let define_array_MEGA = [];

function updateDropdownOptions() {
    let options = textInputs.map(text => [text, text]);
    if (options.length === 0) {
        options.push(['Ninguna', '']);
    }
    return options;
}

function updateDropdownOptions_bool() {
    let options = bool_var_array.map(text => [text, text]);
    if (options.length === 0) {
        options.push(['Ninguna', '']);
    }
    return options;
}
function updateDropdownOptions_char() {
    let options = char_var_array.map(text => [text, text]);
    if (options.length === 0) {
        options.push(['Ninguna', '']);
    }
    return options;
}
function updateDropdownOptions_double() {
    let options = double_var_array.map(text => [text, text]);
    if (options.length === 0) {
        options.push(['Ninguna', '']);
    }
    return options;
}
function updateDropdownOptions_float() {
    let options = float_var_array.map(text => [text, text]);
    if (options.length === 0) {
        options.push(['Ninguna', '']);
    }
    return options;
}
function updateDropdownOptions_int() {
    let options = int_var_array.map(text => [text, text]);
    if (options.length === 0) {
        options.push(['Ninguna', '']);
    }
    return options;
}
function updateDropdownOptions_long() {
    let options = long_var_array.map(text => [text, text]);
    if (options.length === 0) {
        options.push(['Ninguna', '']);
    }
    return options;
}
function updateDropdownOptions_short() {
    let options = short_var_array.map(text => [text, text]);
    if (options.length === 0) {
        options.push(['Ninguna', '']);
    }
    return options;
}
function updateDropdownOptions_string() {
    let options = string_var_array.map(text => [text, text]);
    if (options.length === 0) {
        options.push(['Ninguna', '']);
    }
    return options;
}
function updateDropdownOptions_function() {
    let options = function_array.map(text => [text, text]);
    if (options.length === 0) {
        options.push(['Ninguna', '']);
    }
    return options;
}
function updateDropdownOptions_UNO() {
    let options = define_array_UNO.map(text => [text, text]);
    for (let i = 0; i <= 13; i++) {
        options.push([i.toString(), i.toString()]);
    }
    for (let i = 0; i <= 5; i++) {
        options.push(["A" + i.toString(), "A" + i.toString()]);
    }
    return options;
}
function updateDropdownOptions_UNO_Analog() {
    let options = define_array_UNO.map(text => [text, text]);
    for (let i = 0; i <= 5; i++) {
        options.push(["A" + i.toString(), "A" + i.toString()]);
    }
    return options;
}
function updateDropdownOptions_UNO_Digital() {
    let options = define_array_UNO.map(text => [text, text]);
    for (let i = 0; i <= 13; i++) {
        options.push([i.toString(), i.toString()]);
    }
    return options;
}
function updateDropdownOptions_MEGA() {
    let options = define_array_MEGA.map(text => [text, text]);
    for (let i = 0; i <= 53; i++) {
        options.push([i.toString(), i.toString()]);
    }
    for (let i = 0; i <= 15; i++) {
        options.push(["A" + i.toString(), "A" + i.toString()]);
    }
    return options;
}
function updateDropdownOptions_MEGA_Analog() {
    let options = define_array_MEGA.map(text => [text, text]);
    for (let i = 0; i <= 15; i++) {
        options.push(["A" + i.toString(), "A" + i.toString()]);
    }
    return options;
}
function updateDropdownOptions_MEGA_Digital() {
    let options = define_array_MEGA.map(text => [text, text]);
    for (let i = 0; i <= 53; i++) {
        options.push([i.toString(), i.toString()]);
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
        this.setColour("#008000");
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
        this.setColour("#008000");
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
            .setCheck("int")

            .setAlign(Blockly.ALIGN_RIGHT);
        this.setColour("#008000");
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
        // Aceptar solo letras y n?meros en el valor de entrada
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

