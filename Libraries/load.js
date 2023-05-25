jQuery(document).on("click", "#load_text", function () {
  var fileInput = document.createElement("input");
  fileInput.type = "file";
  fileInput.accept = ".blckmno";

  fileInput.addEventListener("change", function (event) {
    var file = event.target.files[0];
    var reader = new FileReader();
    var loadCount = 0;

    reader.onload = function (event) {
      var xml = event.target.result;
      var dom = Blockly.Xml.textToDom(xml);
      workspace.clear();
      Blockly.Xml.domToWorkspace(dom, workspace);

      // Increment the load count
      loadCount++;

      // Check if it has loaded twice
      if (loadCount < 2) {
        // Wait for some time before loading the file again
        setTimeout(function () {
          reader.readAsText(file);
        }, 500);
      }
    };

    reader.readAsText(file);
  });

  fileInput.click();
});

  /*
  jQuery(document).on("click", "#load_text", function() {
    var workspace = Blockly.getMainWorkspace(); // Get the main workspace

    var fileURL = 'objects/Test.blckmno'; // Este es mi archivo pero no me deja cargarlo por restricciones de CORS, deberia funcionar en web
    
    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();
    xhr.open('GET', fileURL, true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        var xmlText = xhr.responseText;
    
        // Parse the XML text into a DOM structure
        var domParser = new DOMParser();
        var xmlDoc = domParser.parseFromString(xmlText, 'text/xml');
    
        // Extract the root block from the XML
        var rootBlockXml = xmlDoc.getElementsByTagName('xml')[0].firstElementChild;
    
        // Convert the root block XML into a Blockly block
        var rootBlock = Blockly.Xml.domToBlock(rootBlockXml, workspace);
    
        // Add the root block to the workspace
        workspace.getCanvas().setResizesEnabled(false);
        rootBlock.moveBy(20, 20); // Optional: Adjust the position of the loaded blocks
        workspace.getCanvas().setResizesEnabled(true);
      }
    };
    xhr.send();
  });
  */