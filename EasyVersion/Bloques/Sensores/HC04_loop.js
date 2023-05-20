Blockly.Blocks['HC04_loop'] = {
  init: function() {
    // Add attributes
    this.svgGroup_.setAttribute('data-attribute', 'objects');
    this.svgGroup_.setAttribute('special-attribute', 'HC04');
    
    this.appendDummyInput()
        .appendField("Leer distancia HC-04");
    this.setInputsInline(true);
    this.setPreviousStatement(true, "!arduino_case");
    this.setNextStatement(true, "!arduino_case");
    this.setColour('#2a2a2a');
    this.setTooltip('Lee la distancia del sensor HC04');
  }
};

Blockly.JavaScript['HC04_loop'] = function(block) {
  const TR = block.TRIGGER;
  const ECH = block.ECHO;

  const code = `
    //Leer distancia con el sensor HC04\n
    digitalWrite(Trigger, HIGH);\n
    delayMicroseconds(10); //Enviamos un pulso de 10us\n
    digitalWrite(Trigger, LOW);\n
    t = pulseIn(Echo, HIGH); //obtenemos el ancho del pulso\n
    d = t/59; //escalamos el tiempo a una distancia en cm, para metros cambiar a 5900\n
  `;
  return code;
};



