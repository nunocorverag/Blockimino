// Declare a global variable
window.argumentCounter = 0;
window.parameterCounter = 0;
window.includeCounter = 0;
window.setupCounter = 0;
window.typesCounter = 0;
window.namesCounter = 0;
window.basicCounter = 0;

jQuery(document).on("click", "#help", function () {
  if (
    argumentCounter === 0 &&
    parameterCounter === 0 &&
    includeCounter === 0 &&
    setupCounter === 0 &&
    typesCounter === 0 &&
    namesCounter === 0 &&
    basicCounter === 0
  ) {
    alert("Ninguna retroalimentación aún.");
  } else {
    let advice = "Se recomienda repasar los temas:\n";

    if (argumentCounter >= 5) {
      advice += "- Cómo utilizar los argumentos en los bloques de Blockimino (Fuertemente sugerido)\n";
    } else if (argumentCounter > 0){
      advice += "- Cómo utilizar los argumentos en los bloques de Blockimino\n";
    }

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
      advice += "- Tipos de Arduino UNO y MEGA (Fuertemente sugerido)\n";
    } else if (typesCounter > 0) {
      advice += "- Tipos de Arduino UNO y MEGA\n";
    }

    if (namesCounter >= 5) {
        advice += "- Nombres de variables en Arduino (Fuertemente sugerido)\n";
    } else if (namesCounter > 0) {
        advice += "- Nombres de variables en Arduino\n";
    }

    if (basicCounter >= 5) {
      advice += "- Conceptos básicos de programación por bloques (Fuertemente sugerido)\n";
    } else if (basicCounter > 0) {
      advice += "- Conceptos básicos de programación por bloques\n";
    }

    alert(advice);
  }
});