Blockly.Blocks['include'] = {
    init: function () {
        this.svgGroup_.setAttribute('data-attribute', 'include');

        this.appendDummyInput()
            .setAlign(Blockly.ALIGN_RIGHT)
            .appendField("#include")
            .appendField(new Blockly.FieldTextInput("<NombreBiblioteca>", this.textInputValidator), "LIBRARY_NAME");

        this.setPreviousStatement(true, "include"); // Permite conectar el bloque por arriba
        this.setNextStatement(true, "include"); // Permite conectar el bloque por abajo
        this.setColour("#ffcc00");
        this.setTooltip("Incluye una biblioteca en tu proyecto (el nombre es libre a tu necesidad, si no tienes la libreria que indicaste, marcará error al compilar)");
        this.setDeletable(false);
    },

    textInputValidator: function (newValue) {
        // Aceptar solo letras, n�meros, guiones bajos y caracteres de puntuaci�n en el valor de entrada
        var regex = /^[\w<>\/\.\-_]+$/;
        if (regex.test(newValue)) {
            return newValue;
        } else {
            return null;
        }
    }
};

Blockly.JavaScript['include'] = function (block) {
    let libraryName = block.getFieldValue('LIBRARY_NAME');
    var code = '#include ' + libraryName + '\n';
    return code;
};
