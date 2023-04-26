function loadScripts() {
    const head = document.getElementsByTagName('head')[0];
  

    //100 Setup
    //200 EstructurasDeControl (antes Logic)
    //300 Variables (antes parameters)
    //400 Operadores
    //500 Matematicas
    //600 Modulos
    //700 Digital
    //800 Analogico
    //900 Tiempo
    //1000 Otros
    // Create script tags for each script and append them to the head element
    const script101 = document.createElement('script');
    script101.src = 'Bloques/Setup/group_block.js';
    head.appendChild(script101);

    const script201 = document.createElement('script');
    script201.src = 'Bloques/EstructurasDeControl/arduino_for.js';
    head.appendChild(script201);

    const script202 = document.createElement('script');
    script202.src = 'Bloques/EstructurasDeControl/arduino_while.js';
    head.appendChild(script202);

    const script203 = document.createElement('script');
    script203.src = 'Bloques/EstructurasDeControl/arduino_dowhile.js';
    head.appendChild(script203);

    const script204 = document.createElement('script');
    script204.src = 'Bloques/EstructurasDeControl/arduino_if.js';
    head.appendChild(script204);
  
    const script301 = document.createElement('script');
    script301.src = 'Bloques/Variables/param_string.js';
    head.appendChild(script301);
  
    const script302 = document.createElement('script');
    script302.src = 'Bloques/Variables/simple_text.js';
    head.appendChild(script302);

    const script303 = document.createElement('script');
    script303.src = 'Bloques/Variables/variables.js';
    head.appendChild(script303);
  
    const script304 = document.createElement('script');
    script304.src = 'Bloques/Variables/param_float.js';
    head.appendChild(script304);

    const script305 = document.createElement('script');
    script305.src = 'Bloques/Variables/param_int.js';
    head.appendChild(script305);

    const script777 = document.createElement('script');
    script777.src = 'Bloques/Variables/ctype.js';
    head.appendChild(script777);
  }
  
  loadScripts();