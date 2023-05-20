function delete_object(event) {
    event.preventDefault();
    var data = event.dataTransfer.getData("Text");
    var element = document.getElementById(data);
    if (element) {
      // Check if the element's parent is a dropdown-content div
      if (element.parentNode.classList.contains("dropdown-content")) {
        // Don't delete the menu object
        return;
      }
      // Delete the element
      element.parentNode.removeChild(element);
    }
  }
  
  function allow_drop(event) {
    event.preventDefault();
  }