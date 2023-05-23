var lastSearchIndex = -1; // Inicialmente, no se ha encontrado ninguna palabra

function buscarPalabra() {
  // Obtener la palabra buscada del input
  var searchQuery = document.getElementById("search-input").value;
  
  // Realizar la b�squeda
  var found = window.find(searchQuery, false, false, true, false, false, false);
  
  if (found) {
    // Si la palabra es encontrada, desplazarse a la posici�n
    var windowHeight = window.innerHeight;
    var elementTop = window.getSelection().focusNode.parentNode.offsetTop;
    window.scrollTo(0, elementTop - windowHeight/2);
    
    // Actualizar el �ndice de la �ltima palabra encontrada
    lastSearchIndex = window.getSelection().baseOffset;
  } else {
    if (lastSearchIndex !== -1) {
      // Si no se encuentra ninguna palabra y ya se ha realizado una b�squeda anteriormente,
      // volver a buscar desde el principio de la p�gina
      window.getSelection().removeAllRanges(); // Deseleccionar la palabra anterior
      window.scrollTo(0, 0); // Desplazarse al principio de la p�gina
      found = window.find(searchQuery, false, false, true, false, false, false);
      
      if (found) {
        // Si se encuentra la palabra, desplazarse a la posici�n
        var windowHeight = window.innerHeight;
        var elementTop = window.getSelection().focusNode.parentNode.offsetTop;
        window.scrollTo(0, elementTop - windowHeight/2);
        
        // Actualizar el �ndice de la �ltima palabra encontrada
        lastSearchIndex = window.getSelection().baseOffset;
      } else {
        // Si no se encuentra la palabra, mostrar un mensaje
        alert("Palabra no encontrada");
      }
    } else {
      // Si no se encuentra la palabra y no se ha realizado ninguna b�squeda anteriormente,
      // mostrar un mensaje
      alert("Palabra no encontrada");
    }
  }
}

// Asignar el evento click al bot�n de b�squeda
document.getElementById("search-button").addEventListener("click", buscarPalabra);
