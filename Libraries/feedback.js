// Declare a global variable
window.parameterCounter = 0;
window.includeCounter = 0;
window.setupCounter = 0;
window.typesCounter = 0;
window.namesCounter = 0;

jQuery(document).on("click", "#help", function () {
  if (
    parameterCounter === 0 &&
    includeCounter === 0 &&
    setupCounter === 0 &&
    typesCounter === 0 &&
    namesCounter === 0
  ) {
    alert("Ninguna retroalimentación aún.");
  } else {
    let advice = "Se recomienda repasar los temas:\n";

    if (parameterCounter >= 5) {
      advice += "- Cómo utilizar parámetros en Arduino (Fuertemente sugerido)\n";
    } else if (parameterCounter > 0){
      advice += "- Cómo utilizar parámetros en Arduino\n";
    }

    if (includeCounter >= 5) {
      advice += "- Incluir bibliotecas en Arduino (Fuertemente sugerido)\n";
    } else if (includeCounter > 0) {
      advice += "- Incluir bibliotecas en Arduino\n";
    }
  
    if (setupCounter >= 5) {
      advice += "- Función setup() en Arduino (Fuertemente sugerido)\n";
    } else if (setupCounter > 0) {
      advice += "- Función setup() en Arduino\n";
    }
  
    if (typesCounter >= 5) {
      advice += "- Tipos de datos en Arduino (Fuertemente sugerido)\n";
    } else if (typesCounter > 0) {
      advice += "- Tipos de datos en Arduino\n";
    }

    if (namesCounter >= 5) {
        advice += "- Nombres de variables en Arduino (Fuertemente sugerido)\n";
    } else if (namesCounter > 0) {
        advice += "- Nombres de variables en Arduino\n";
    }

    alert(advice);
  }
});