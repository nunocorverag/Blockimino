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
  
  jQuery(document).on("click", "#import_text", function () {
    var xml = Blockly.Xml.domToPrettyText(Blockly.Xml.workspaceToDom(workspace));
    var filename = "BlockiminoImport.blckmno";
    downloadFile(filename, xml);
  });