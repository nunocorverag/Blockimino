var lastSearchIndex = -1; // Inicialmente, no se ha encontrado ninguna palabra

function buscarPalabra() {
  // Obtener la palabra buscada del input
  var searchQuery = document.getElementById("search-input").value;
  
  // Realizar la búsqueda
  var found = window.find(searchQuery, false, false, true, false, false, false);
  
  if (found) {
    // Si la palabra es encontrada, desplazarse a la posición
    var windowHeight = window.innerHeight;
    var elementTop = window.getSelection().focusNode.parentNode.offsetTop;
    window.scrollTo(0, elementTop - windowHeight/2);
    
    // Actualizar el índice de la última palabra encontrada
    lastSearchIndex = window.getSelection().baseOffset;
  } else {
    if (lastSearchIndex !== -1) {
      // Si no se encuentra ninguna palabra y ya se ha realizado una búsqueda anteriormente,
      // volver a buscar desde el principio de la página
      window.getSelection().removeAllRanges(); // Deseleccionar la palabra anterior
      window.scrollTo(0, 0); // Desplazarse al principio de la página
      found = window.find(searchQuery, false, false, true, false, false, false);
      
      if (found) {
        // Si se encuentra la palabra, desplazarse a la posición
        var windowHeight = window.innerHeight;
        var elementTop = window.getSelection().focusNode.parentNode.offsetTop;
        window.scrollTo(0, elementTop - windowHeight/2);
        
        // Actualizar el índice de la última palabra encontrada
        lastSearchIndex = window.getSelection().baseOffset;
      } else {
        // Si no se encuentra la palabra, mostrar un mensaje
        alert("Palabra no encontrada");
      }
    } else {
      // Si no se encuentra la palabra y no se ha realizado ninguna búsqueda anteriormente,
      // mostrar un mensaje
      alert("Palabra no encontrada");
    }
  }
}

// Asignar el evento click al botón de búsqueda
document.getElementById("search-button").addEventListener("click", buscarPalabra);
