
jQuery(document).on("click", "#verify_text", function () {
  if (Blockly.mainWorkspace.getTopBlocks().length === 0) {
    alert("La arena de bloques esta vacia!");
  } else {
  
     /*
    var setup = []; // Create an array to hold the generated text
    var sets = document.querySelectorAll("#blocklyDiv .setup[data-attribute]");
    sets.forEach(function(sets) {
      setup.push(sets.getAttribute("data-attribute")); // Generate text based on the attributes of the objects for each object
    });
    */
  
    // Check if there is an incomplete while block
    var incomplete = false;
    var whileBlocks = workspace.getBlocksByType('arduino_while');
    whileBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'CONDITION1', Blockly.JavaScript.ORDER_ATOMIC);
      var condition2 = Blockly.JavaScript.valueToCode(block, 'CONDITION2', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1 || !condition2) {
        incomplete = true;
      }
    });
    var dowhileBlocks = workspace.getBlocksByType('arduino_dowhile');
    dowhileBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'CONDITION1', Blockly.JavaScript.ORDER_ATOMIC);
      var condition2 = Blockly.JavaScript.valueToCode(block, 'CONDITION2', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1 || !condition2) {
        incomplete = true;
      }
    });
    var ifBlocks = workspace.getBlocksByType('arduino_if');
    ifBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'CONDITION1', Blockly.JavaScript.ORDER_ATOMIC);
      var condition2 = Blockly.JavaScript.valueToCode(block, 'CONDITION2', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1 || !condition2) {
        incomplete = true;
      }
    });
    var ifelseBlocks = workspace.getBlocksByType('arduino_ifelse');
    ifelseBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'CONDITION1', Blockly.JavaScript.ORDER_ATOMIC);
      var condition2 = Blockly.JavaScript.valueToCode(block, 'CONDITION2', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1 || !condition2) {
        incomplete = true;
      }
    });
    var forBlocks = workspace.getBlocksByType('arduino_for');
    forBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'START', Blockly.JavaScript.ORDER_ATOMIC);
      var condition2 = Blockly.JavaScript.valueToCode(block, 'END', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1 || !condition2) {
        incomplete = true;
      }
    });
    var switchBlocks = workspace.getBlocksByType('arduino_switch');
    switchBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'VALUE', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1) {
        incomplete = true;
      }
    });
    var caseBlocks = workspace.getBlocksByType('arduino_case');
    caseBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'VALUE', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1) {
        incomplete = true;
      }
    });
  
    var absBlocks = workspace.getBlocksByType('arduino_abs');
    absBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'VALUE', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1) {
        incomplete = true;
      }
    });
    var constrainBlocks = workspace.getBlocksByType('arduino_constrain');
    constrainBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'VALUE', Blockly.JavaScript.ORDER_ATOMIC);
      var condition2 = Blockly.JavaScript.valueToCode(block, 'LOWER_BOUND', Blockly.JavaScript.ORDER_ATOMIC);
      var condition3 = Blockly.JavaScript.valueToCode(block, 'UPPER_BOUND', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1 || !condition2 || !condition3) {
        incomplete = true;
      }
    });
    var mapBlocks = workspace.getBlocksByType('arduino_map');
    mapBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'VALUE', Blockly.JavaScript.ORDER_ATOMIC);
      var condition2 = Blockly.JavaScript.valueToCode(block, 'FROM_LOW', Blockly.JavaScript.ORDER_ATOMIC);
      var condition3 = Blockly.JavaScript.valueToCode(block, 'FROM_HIGH', Blockly.JavaScript.ORDER_ATOMIC);
      var condition4 = Blockly.JavaScript.valueToCode(block, 'TO_LOW', Blockly.JavaScript.ORDER_ATOMIC);
      var condition5 = Blockly.JavaScript.valueToCode(block, 'TO_HIGH', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1 || !condition2 || !condition3 || !condition4 || !condition5) {
        incomplete = true;
      }
    });
    var maxBlocks = workspace.getBlocksByType('arduino_max');
    maxBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'VALUE1', Blockly.JavaScript.ORDER_ATOMIC);
      var condition2 = Blockly.JavaScript.valueToCode(block, 'VALUE2', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1 || !condition2) {
        incomplete = true;
      }
    });
    var minBlocks = workspace.getBlocksByType('arduino_min');
    minBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'VALUE1', Blockly.JavaScript.ORDER_ATOMIC);
      var condition2 = Blockly.JavaScript.valueToCode(block, 'VALUE2', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1 || !condition2) {
        incomplete = true;
      }
    });
    var powBlocks = workspace.getBlocksByType('arduino_pow');
    powBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'VALUE', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1) {
        incomplete = true;
      }
    });
    var randomBlocks = workspace.getBlocksByType('arduino_random');
    randomBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'MIN', Blockly.JavaScript.ORDER_ATOMIC);
      var condition2 = Blockly.JavaScript.valueToCode(block, 'MAX', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1 || !condition2) {
        incomplete = true;
      }
    });
    var sqBlocks = workspace.getBlocksByType('arduino_sq');
    sqBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'VALUE', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1) {
        incomplete = true;
      }
    });
    var sqrtBlocks = workspace.getBlocksByType('arduino_sqrt');
    sqrtBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'VALUE', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1) {
        incomplete = true;
      }
    });
  
    var setCursorBlocks = workspace.getBlocksByType('LCDsetCursor');
    setCursorBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'COL', Blockly.JavaScript.ORDER_ATOMIC);
      var condition2 = Blockly.JavaScript.valueToCode(block, 'ROW', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1 || !condition2) {
        incomplete = true;
      }
    });
    var writeBlocks = workspace.getBlocksByType('LCDwrite');
    writeBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'TEXT', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1) {
        incomplete = true;
      }
    });
    var printBlocks = workspace.getBlocksByType('LCDprint');
    printBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'TEXT', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1) {
        incomplete = true;
      }
    });
  
    var arithmeticBlocks = workspace.getBlocksByType('arithmetic_operator');
    arithmeticBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'OPERAND1', Blockly.JavaScript.ORDER_ATOMIC);
      var condition2 = Blockly.JavaScript.valueToCode(block, 'OPERAND2', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1 || !condition2) {
        incomplete = true;
      }
    });
    var booleanBlocks = workspace.getBlocksByType('boolean_operator');
    booleanBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'OPERAND1', Blockly.JavaScript.ORDER_ATOMIC);
      var condition2 = Blockly.JavaScript.valueToCode(block, 'OPERAND2', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1 || !condition2) {
        incomplete = true;
      }
    });
    var comparisonBlocks = workspace.getBlocksByType('comparison_operator');
    comparisonBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'OPERAND1', Blockly.JavaScript.ORDER_ATOMIC);
      var condition2 = Blockly.JavaScript.valueToCode(block, 'OPERAND2', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1 || !condition2) {
        incomplete = true;
      }
    });
    var updaterBlocks = workspace.getBlocksByType('updater_operator');
    updaterBlocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'OPERAND1', Blockly.JavaScript.ORDER_ATOMIC);
      var condition2 = Blockly.JavaScript.valueToCode(block, 'OPERAND2', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1 || !condition2) {
        incomplete = true;
      }
    });
    var UNOinterruptionBLocks = workspace.getBlocksByType('arduino_interrupt');
    UNOinterruptionBLocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'FUNC_NAME', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1) {
        incomplete = true;
      }
    });
    var MEGAinterruptionBLocks = workspace.getBlocksByType('MEGA_arduino_interrupt');
    MEGAinterruptionBLocks.forEach(function(block) {
      var condition1 = Blockly.JavaScript.valueToCode(block, 'FUNC_NAME', Blockly.JavaScript.ORDER_ATOMIC);
      if (!condition1) {
        incomplete = true;
      }
    });
    
  
    
  
    if (incomplete) {
      alert("Hay bloques con parametros faltantes :(");
      window.argumentCounter++;
    } else {
      var includeBlocks = Blockly.mainWorkspace.getTopBlocks().filter(function(block) {
        return block.svgGroup_ && block.svgGroup_.getAttribute('data-attribute') === 'include';
      });
      var includeCode = "";
      for (var i = 0; i < includeBlocks.length; i++) {
          var inc = Blockly.JavaScript.blockToCode(includeBlocks[i]);
          if (inc) {
            includeCode += inc + "\n";
          }
      }
  
  
      var declarationBlocks = Blockly.mainWorkspace.getTopBlocks().filter(function(block) {
        return block.svgGroup_ && block.svgGroup_.getAttribute('data-attribute') === 'declarations';
      });
      var declarationCode = "";
      for (var i = 0; i < declarationBlocks.length; i++) {
        var dec = Blockly.JavaScript.blockToCode(declarationBlocks[i]);
        if (dec) {
          declarationCode += dec + "\n";
        }
      }
  
  
      var setupBlocks = Blockly.mainWorkspace.getTopBlocks().filter(function(block) {
        return block.svgGroup_ && block.svgGroup_.getAttribute('data-attribute') === 'setup';
      });
      var setupCode = "";
      for (var i = 0; i < setupBlocks.length; i++) {
          var set = Blockly.JavaScript.blockToCode(setupBlocks[i]);
          if (set) {
              setupCode += set + "\n";
          }
      }
  
  
      var objectBlocks = Blockly.mainWorkspace.getTopBlocks().filter(function(block) {
        return block.svgGroup_ && block.svgGroup_.getAttribute('data-attribute') === 'objects';
      });
      var objectCode = "";
      for (var i = 0; i < objectBlocks.length; i++) {
        var code = Blockly.JavaScript.blockToCode(objectBlocks[i]);
        if (code) {
          objectCode += code + "\n";
        }
      }
  
      
      var functionBlocks = Blockly.mainWorkspace.getTopBlocks().filter(function(block) {
        return block.svgGroup_ && block.svgGroup_.getAttribute('data-attribute') === 'functions';
      });
      var functionCode = "";
      for (var i = 0; i < functionBlocks.length; i++) {
        var func = Blockly.JavaScript.blockToCode(functionBlocks[i]);
        if (func) {
          functionCode += func + "\n";
        }
      }
  
  
  
      // Check if LCD blocks are present
      var hasLCD = false;
      Blockly.mainWorkspace.getAllBlocks().forEach(function(block) {
        if (block.svgGroup_.getAttribute('special-attribute') === 'LCD') {
          hasLCD = true;
        }
      });
      // Check if includeLCD block is present
      var hasIncludeLCD = false;
      Blockly.mainWorkspace.getAllBlocks().forEach(function(block) {
        if (block.type === "includeLCD") {
          hasIncludeLCD = true;
        }
      });
      // Check if includeLCD block is present
      Blockly.mainWorkspace.getAllBlocks().forEach(function(block) {
        if (block.type === "includeLCD_MEGA") {
          hasIncludeLCD = true;
        }
      });
      // Check if LCDbegin block is present
      var hasLCDbegin = false;
      Blockly.mainWorkspace.getAllBlocks().forEach(function(block) {
        if (block.type === "LCDbegin") {
          hasLCDbegin = true;
        }
      });
      // Check for valid combinations
      if (hasLCD && !hasIncludeLCD && !hasLCDbegin) {
      // Invalid if there are lcd commands and it is missing either lcd begin or lcd include
      alert("Falta incluir el LCD y/o inicializarlo con lcd.begin!");
      window.includeCounter++;
      window.setupCounter++;
      return;
      } else if (hasLCDbegin && !hasIncludeLCD) {
      // Invalid if there are no lcd commands and just the begin
      alert("Falta incluir la librería LCD con #include!");
      window.includeCounter++;
      return;
      } else if (hasIncludeLCD && !hasLCDbegin) {
      // Invalid if there are no lcd commands and just the include
      alert("Falta inicializar el LCD con lcd.begin!");
      window.setupCounter++;
      return;
      } else if (hasLCD && hasIncludeLCD && hasLCDbegin) {
      // Valid if there are lcd commands and both begin and include
      // Do nothing, continue with program
      } else if (!hasLCD && hasIncludeLCD && hasLCDbegin) {
      // Valid if there are no lcd commands and both begin and include
      // Do nothing, continue with program
      }
  
  
      // Check if HC04 blocks are present
      var hasHC04 = false;
      Blockly.mainWorkspace.getAllBlocks().forEach(function(block) {
        if (block.svgGroup_.getAttribute('special-attribute') === 'HC04') {
          hasHC04 = true;
        }
      });
      // Check if includeLCD block is present
      var hasIncludeHC04 = false;
      Blockly.mainWorkspace.getAllBlocks().forEach(function(block) {
        if (block.type === "HC04_include") {
          hasIncludeHC04 = true;
        }
      });
      // Check if LCDbegin block is present
      var hasHC04begin = false;
      Blockly.mainWorkspace.getAllBlocks().forEach(function(block) {
        if (block.type === "HC04_begin") {
          hasHC04begin = true;
        }
      });
      // Check for valid combinations
      if (hasHC04 && !hasIncludeHC04 && !hasHC04begin) {
      // Invalid if there are lcd commands and it is missing either lcd begin or lcd include
      alert("Falta incluir el HC04 y/o configurarlo!");
      window.includeCounter++;
      return;
      } else if (hasHC04begin && !hasIncludeHC04) {
      // Invalid if there are no lcd commands and just the begin
      alert("Falta definir la librería HC04!");
      window.includeCounter++;
      return;
      } else if (hasIncludeHC04 && !hasHC04begin) {
      // Invalid if there are no lcd commands and just the include
      alert("Falta configurar el HC04!");
      window.setupCounter++;
      return;
      } else if (hasHC04 && hasIncludeHC04 && hasHC04begin) {
      // Valid if there are lcd commands and both begin and include
      // Do nothing, continue with program
      } else if (!hasHC04 && hasIncludeHC04 && hasHC04begin) {
      // Valid if there are no lcd commands and both begin and include
      // Do nothing, continue with program
      }
  
  
  
      // Check if serial.begin is included
      var hasSerial = false;
      Blockly.mainWorkspace.getAllBlocks().forEach(function(block) {
          if (block.svgGroup_.getAttribute('special-attribute') === 'serial') {
              hasSerial = true;
          }
      });
      // Check if serial_begin block is present
      var hasIncludeSerial = false;
      Blockly.mainWorkspace.getAllBlocks().forEach(function(block) {
          if (block.type == "serial_begin") {
              hasIncludeSerial = true;
          }
      });
      // Alert and return if serial blocks are present and serial_begin block is absent
      if (hasSerial && !hasIncludeSerial) {
          alert("Se tiene que inicializar el serial antes de utilizarlo!");
          window.setupCounter++;
          return;
      }
      
  
  
      // Check if Teclado blocks are present
      var hasTeclado = false;
      Blockly.mainWorkspace.getAllBlocks().forEach(function(block) {
        if (block.svgGroup_.getAttribute('special-attribute') === 'Teclado') {
          hasTeclado = true;
        }
      });
      // Check if Teclado_include block is present
      var hasIncludeTeclado = false;
      Blockly.mainWorkspace.getAllBlocks().forEach(function(block) {
        if (block.type === "Teclado_include") {
          hasIncludeTeclado = true;
        }
      });
      // Alert and return if Teclado blocks are present and Teclado_include block is absent
      if (hasTeclado && !hasIncludeTeclado) {
        alert("Se tiene que incluir el Teclado para poder usarlo!");
        window.includeCounter++;
        return;
      }
  
  
      // Check if LDR blocks are included
      var hasLDR = false;
      Blockly.mainWorkspace.getAllBlocks().forEach(function(block) {
          if (block.svgGroup_.getAttribute('special-attribute') === 'LDR') {
              hasLDR = true;
          }
      });
      // Check if LDR_include block is present
      var hasIncludeLDR = false;
      Blockly.mainWorkspace.getAllBlocks().forEach(function(block) {
          if (block.type == "LDR_include") {
              hasIncludeLDR = true;
          }
      });
      // Alert and return if LDR blocks are present and LDR_include block is absent
      if (hasLDR && !hasIncludeLDR) {
          alert("Se tiene que definir el LDR para poder usarlo!");
          window.includeCounter++;
          return;
      }
  
  
  
      // Check if LDR blocks are included
      var hasLDR = false;
      Blockly.mainWorkspace.getAllBlocks().forEach(function(block) {
          if (block.svgGroup_.getAttribute('special-attribute') === 'LDR') {
              hasLDR = true;
          }
      });
      // Check if LDR_include block is present
      var hasIncludeLDR = false;
      Blockly.mainWorkspace.getAllBlocks().forEach(function(block) {
          if (block.type == "LDR_include") {
              hasIncludeLDR = true;
          }
      });
      // Alert and return if LDR blocks are present and LDR_include block is absent
      if (hasLDR && !hasIncludeLDR) {
          alert("Se tiene que definir el LDR para poder usarlo!");
          window.includeCounter++;
          return;
      }
  
  
  
  
  
  
      // Check if UNO or MEGA blocks are included
      var hasUNO = false;
      var hasMEGA = false;
      Blockly.mainWorkspace.getAllBlocks().forEach(function(block) {
          if (block.svgGroup_.getAttribute('special-attribute') === 'UNO') {
              hasUNO = true;
          }
          if (block.svgGroup_.getAttribute('special-attribute') === 'MEGA') {
              hasMEGA = true;
          }
      });
      // Alert and return if UNO or MEGA blocks are present together
      if (hasUNO && hasMEGA) {
          alert("No se pueden usar bloques de UNO y MEGA al mismo tiempo!");
          window.typesCounter++;
          return;
      }
  
  
  
  
  
  
  
      // Get all blocks of type create_bool, create_int, and create_float
      const blocks = workspace.getAllBlocks().filter(block => {
        return ['create_bool', 'create_char', 'create_string', 'create_int', 'create_long', 'create_short', 'create_float', 'create_double', 'create_define_UNO', 'create_define_MEGA', 'create_function', 'create_void_function'].includes(block.type);
      });
  
      // Get all unique variable names
      const variableNames = blocks.map(block => block.getFieldValue('TEXT_INPUT')).filter(name => name.trim() !== '');
      const uniqueVariableNames = [...new Set(variableNames)];
  
      // If there are duplicate variable names, send an alert and return
      if (uniqueVariableNames.length !== variableNames.length) {
        alert('Los nombres de las variables o funciones no pueden ser repetidos!');
        window.namesCounter++;
        return;
      }
  

      // Get all PIN values from the specific blocks
      const pinValues = [];
      ['arduino_digital_read', 'MEGA_arduino_digital_read', 'arduino_digital_write', 'MEGA_arduino_digital_write', 'arduino_analog_read', 'MEGA_arduino_analog_read'].forEach(blockType => {
        workspace.getAllBlocks().filter(block => block.type === blockType).forEach(block => {
          const pinValue = block.getFieldValue('PIN');
          // Check if the pinValue is within the ignored range
          if (!(pinValue >= 0 && pinValue <= 53) && !(/^A\d{1,2}$/).test(pinValue)) {
            pinValues.push(pinValue);
          }
        });
      });
  
      // Check if all PIN values have appeared in unique variable names
      const missingPins = pinValues.filter(pinValue => !uniqueVariableNames.includes(pinValue));
      if (missingPins.length > 0) {
        alert('Los pines declarados en uso deben coincidir con sus definiciones!');
        window.namesCounter++;
        return;
      }
  
      // Get all DROPDOWN_LIST values from the specific blocks
      const dropdownValues = [];
      ['bool_list', 'char_list', 'double_list', 'float_list', 'int_list', 'long_list', 'short_list', 'string_list', 'function_list', 'function_list_value'].forEach(blockType => {
        workspace.getAllBlocks().filter(block => block.type === blockType).forEach(block => {
          const dropdownValue = block.getFieldValue('DROPDOWN_LIST');
          dropdownValues.push(dropdownValue);
        });
      });
  
      // Check if all DROPDOWN_LIST values have appeared in unique variable names
      const missingDropdowns = dropdownValues.filter(dropdownValue => !uniqueVariableNames.includes(dropdownValue));
      if (missingDropdowns.length > 0) {
        alert('Las variables utilizadas deben coincidir con sus definiciones!');
        window.namesCounter++;
        return;
      }
  
      
      
  
  
  
      // Get all blocks of type create_bool, create_int, create_float, create_double, create_char, create_string, create_long, create_short, create_define_UNO, create_define_MEGA
      const reservedblocks = workspace.getAllBlocks().filter(block => {
        return ['create_bool', 'create_int', 'create_float', 'create_double', 'create_char', 'create_string', 'create_long', 'create_short', 'create_define_UNO', 'create_define_MEGA'].includes(block.type);
      });
  
      // Get all variable names
      const reservedNames = reservedblocks.map(block => block.getFieldValue('TEXT_INPUT')).filter(name => name.trim() !== '');
  
      // Check if variable names include prohibited names
      const prohibitedNames = ['keys', 'Key', 'rowPins', 'colPins', 'Keypad', 'keypad', 'rows', 'cols', 'Trigger', 'Echo', 't', 'd', 'sensorLuz', 'valorLuz'];
      const duplicateProhibitedNames = reservedNames.filter(name => prohibitedNames.includes(name));
  
      // If there are duplicate variable names or prohibited names, send an alert and return
      if (reservedNames.length !== new Set(reservedNames).size || duplicateProhibitedNames.length > 0) {
        alert('Las variables y definiciones no pueden tener nombres reservados para los modulos, intenta otros nombres');
        return;
      }
  
        var blocksDiv = workspace.getAllBlocks();
        var hasDivisionByZero = false;
      
        // Check each arithmetic_operator block for division by zero
        for (var i = 0; i < blocksDiv.length; i++) {
          var block = blocksDiv[i];
          if (block.type === 'arithmetic_operator') {
            var operator = block.getFieldValue('OPERATOR');
            if (operator === '/') {
              var operand2Block = block.getInputTargetBlock('OPERAND2');
              if (
                operand2Block &&
                [
                  'int_value',
                  'short_value',
                  'long_value',
                  'double_value',
                  'float_value',
                  'bool_value',
                  'char_value',
                  'string_value'
                ].includes(operand2Block.type)
              ) {
                var value = String(operand2Block.getFieldValue('VALUE'));
                if (value === '0') {
                  hasDivisionByZero = true;
                  break;
                }
              }
            }
          }
        }
      
      if (hasDivisionByZero) {
        alert('Error: Division entre cero detectada!');
        return;
      }
  
  
  
  
  /*
      // Get all blocks of type create_bool, create_int, and create_float
      const functionblocks = workspace.getAllBlocks().filter(block => {
        return ['create_function', ,'create_void_function'].includes(block.type);
      });
      
      // Get all unique variable names
      const functionNames = functionblocks.map(block => block.getFieldValue('TEXT_INPUT')).filter(name => name.trim() !== '');
      const uniqueFunctionNames = [...new Set(functionNames)];
      
      // If there are duplicate variable names, send an alert and return
      if (uniqueFunctionNames.length !== functionNames.length) {
        alert('Los nombres de las funciones no pueden ser repetidos');
        return;
      }
  
  */
  
      const pinblocks = workspace.getAllBlocks().filter(block => {
        return ['includeLCD', 'includeLCD_MEGA', 'Teclado_include', 'Teclado_include_MEGA', 'pinMode', 'pinMode_MEGA', 'create_define_UNO', 'create_define_MEGA'].includes(block.type);
      });
      
      // Get all unique pin numbers
      const pinNumbers = pinblocks.flatMap(block => {
        switch (block.type) {
          case 'includeLCD':
          case 'includeLCD_MEGA':
            return [
              block.getFieldValue('RS'),
              block.getFieldValue('RW'),
              block.getFieldValue('ENABLE'),
              block.getFieldValue('D0'),
              block.getFieldValue('D1'),
              block.getFieldValue('D2'),
              block.getFieldValue('D3'),
              block.getFieldValue('D4'),
              block.getFieldValue('D5'),
              block.getFieldValue('D6'),
              block.getFieldValue('D7'),
            ];
          case 'Teclado_include':
          case 'Teclado_include_MEGA':
            return [
              block.getFieldValue('RP1'),
              block.getFieldValue('RP2'),
              block.getFieldValue('RP3'),
              block.getFieldValue('RP4'),
              block.getFieldValue('CP1'),
              block.getFieldValue('CP2'),
              block.getFieldValue('CP3'),
              block.getFieldValue('CP4'),
            ];
          case 'HC04_include':
          case 'HC04_include_MEGA':
            return [
              block.getFieldValue('TRIGGER'),
              block.getFieldValue('ECHO'),
            ];
          case 'LDR_include':
          case 'LDR_include_MEGA':
            return [
              block.getFieldValue('sensorLuz'),
              block.getFieldValue('valorLuz'),
            ];
          case 'pinMode':
          case 'pinMode_MEGA':
          case 'create_define_UNO':
          case 'create_define_MEGA':
            return [block.getFieldValue('PIN')];
          default:
            return [];
        }
      }).filter(pin => pin.trim() !== '');
      
      const uniquePinNumbers = [...new Set(pinNumbers)];
      
      // If there are duplicate pin numbers, send an alert and return
      if (uniquePinNumbers.length !== pinNumbers.length) {
        alert('Los números de los pines no pueden ser repetidos');
        window.namesCounter++;
        return;
      }
  
  
  
  
      //No alerts, code is correct
      alert("Codigo correcto!");
      return;
    }
  }
  });