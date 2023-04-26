//DOWNLOAD TEXT
function downloadFile(filename, text) {
  var element = document.createElement('a');
  element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
  element.setAttribute('download', filename);

  element.style.display = 'none';
  document.body.appendChild(element);

  element.click();

  document.body.removeChild(element);
}

jQuery(document).on("click", "#export_text", function () {
  
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
  var forBlocks = workspace.getBlocksByType('arduino_for');
  forBlocks.forEach(function(block) {
    var condition1 = Blockly.JavaScript.valueToCode(block, 'START', Blockly.JavaScript.ORDER_ATOMIC);
    var condition2 = Blockly.JavaScript.valueToCode(block, 'END', Blockly.JavaScript.ORDER_ATOMIC);
    if (!condition1 || !condition2) {
      incomplete = true;
    }
  });

  if (incomplete) {
    alert("Hay bloques con parametros faltantes :(");
  } else {
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


    var declarationBlocks = Blockly.mainWorkspace.getTopBlocks().filter(function(block) {
      return block.svgGroup_ && block.svgGroup_.getAttribute('data-attribute') === 'declarations';
    });
    var declarationCode = "";
    for (var i = 0; i < declarationBlocks.length; i++) {
      var code = Blockly.JavaScript.blockToCode(declarationBlocks[i]);
      if (code) {
        declarationCode += code + "\n";
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
  
    var workCode = declarationCode + objectCode;



    // Add prefix and suffix
    var prefixset = "void setup() {\n";
    var suffixset = "}\n\n";
    var prefixloop = "void loop() {\n";
    var suffixloop = "\n}\n";
    var output = prefixset + setupCode + "\n" + suffixset + prefixloop + workCode + "\n" + suffixloop;

    var filename = "Blockimino.txt";
    downloadFile(filename, output);
  }
});