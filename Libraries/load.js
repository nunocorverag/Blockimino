jQuery(document).on("click", "#load_text", function () {
    var fileInput = document.createElement("input");
    fileInput.type = "file";
    fileInput.accept = ".blckmno";
  
    fileInput.addEventListener("change", function (event) {
      var file = event.target.files[0];
      var reader = new FileReader();
  
      reader.onload = function (event) {
        var xml = event.target.result;
        var dom = Blockly.Xml.textToDom(xml);
        workspace.clear();
        Blockly.Xml.domToWorkspace(dom, workspace);
      };
  
      reader.readAsText(file);
    });
  
    fileInput.click();
  });