function loadScripts() {
    const head = document.getElementsByTagName('head')[0];

    //100 Setup (antes sintaxis adicional) Cafe -> Naranja
    //200 EstructurasDeControl (antes Logic) Naranja -> Dorado
    //300 Variables (antes parameters) Verde *posiblemente tonalidades*
    //400 Operadores Turquesa -> cafe
    //500 Matematicas Azul celeste -> Azul
    //600 Modulos -> LCD Rojo
    //700 Digital Amarillo this.setColour('#ffb347'); -> Durazno 
    //800 Analogico Morado this.setColour('#8F00FF');
    //900 Tiempo Rosa
    //1000 Otros -> Serial  gris -> azul perry/celeste
    //1100 Sensores
    //1200 Funciones gris

    //6900 Pruebas
    // Create script tags for each script and append them to the head element
    const script101 = document.createElement('script');
    script101.src = 'Bloques/Setup/pinMode.js';
    head.appendChild(script101);

    const script102 = document.createElement('script');
    script102.src = 'Bloques/Setup/includeLCD.js';
    head.appendChild(script102);

    const script103 = document.createElement('script');
    script103.src = 'Bloques/Setup/serial_begin.js';
    head.appendChild(script103);

    const script104 = document.createElement('script');
    script104.src = 'Bloques/Setup/lcd_begin.js';
    head.appendChild(script104);

    const script105 = document.createElement('script');
    script105.src = 'Bloques/Setup/define.js';
    head.appendChild(script105);

    const script106 = document.createElement('script');
    script106.src = 'Bloques/Setup/include.js';
    head.appendChild(script106);

    const script107 = document.createElement('script');
    script107.src = 'Bloques/Setup/define_value.js';
    head.appendChild(script107);

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

    const script205 = document.createElement('script');
    script205.src = 'Bloques/EstructurasDeControl/arduino_ifelse.js';
    head.appendChild(script205);

    const script206 = document.createElement('script');
    script206.src = 'Bloques/EstructurasDeControl/arduino_switch.js';
    head.appendChild(script206);

    const script207 = document.createElement('script');
    script207.src = 'Bloques/EstructurasDeControl/arduino_case.js';
    head.appendChild(script207);
    
    /*
    const script301 = document.createElement('script');
    script301.src = 'Bloques/Variables/param_string.js';
    head.appendChild(script301);

    const script302 = document.createElement('script');
    script302.src = 'Bloques/Variables/param_int.js';
    head.appendChild(script302);

    const script304 = document.createElement('script');
    script304.src = 'Bloques/Variables/param_float.js';
    head.appendChild(script304);
    */
    const script303 = document.createElement('script');
    script303.src = 'Bloques/Variables/variables.js';
    head.appendChild(script303);
    
    const script305 = document.createElement('script');
    script305.src = 'Bloques/Variables/bool.js';
    head.appendChild(script305);

    const script306 = document.createElement('script');
    script306.src = 'Bloques/Variables/char.js';
    head.appendChild(script306);

    const script307 = document.createElement('script');
    script307.src = 'Bloques/Variables/double.js';
    head.appendChild(script307);

    const script308 = document.createElement('script');
    script308.src = 'Bloques/Variables/float.js';
    head.appendChild(script308);

    const script309 = document.createElement('script');
    script309.src = 'Bloques/Variables/int.js';
    head.appendChild(script309);

    const script310 = document.createElement('script');
    script310.src = 'Bloques/Variables/long.js';
    head.appendChild(script310);

    const script311 = document.createElement('script');
    script311.src = 'Bloques/Variables/short.js';
    head.appendChild(script311);

    const script312 = document.createElement('script');
    script312.src = 'Bloques/Variables/string.js';
    head.appendChild(script312);

    const script313 = document.createElement('script');
    script313.src = 'Bloques/Variables/bool_value.js';
    head.appendChild(script313);

    const script314 = document.createElement('script');
    script314.src = 'Bloques/Variables/char_value.js';
    head.appendChild(script314);

    const script315 = document.createElement('script');
    script315.src = 'Bloques/Variables/double_value.js';
    head.appendChild(script315);

    const script316 = document.createElement('script');
    script316.src = 'Bloques/Variables/float_value.js';
    head.appendChild(script316);

    const script317 = document.createElement('script');
    script317.src = 'Bloques/Variables/int_value.js';
    head.appendChild(script317);

    const script318 = document.createElement('script');
    script318.src = 'Bloques/Variables/long_value.js';
    head.appendChild(script318);

    const script319 = document.createElement('script');
    script319.src = 'Bloques/Variables/short_value.js';
    head.appendChild(script319);

    const script320 = document.createElement('script');
    script320.src = 'Bloques/Variables/string_value.js';
    head.appendChild(script320);


    const script401 = document.createElement('script');
    script401.src = 'Bloques/Operadores/arithmetic_operator.js';
    head.appendChild(script401);

    const script402 = document.createElement('script');
    script402.src = 'Bloques/Operadores/boolean_operator.js';
    head.appendChild(script402);

    const script403 = document.createElement('script');
    script403.src = 'Bloques/Operadores/comparison_operator.js';
    head.appendChild(script403);

    const script404 = document.createElement('script');
    script404.src = 'Bloques/Operadores/updater_operator.js';
    head.appendChild(script404);


    const script501 = document.createElement('script');
    script501.src = 'Bloques/Matematicas/abs.js';
    head.appendChild(script501);

    const script502 = document.createElement('script');
    script502.src = 'Bloques/Matematicas/constrain.js';
    head.appendChild(script502);

    const script503 = document.createElement('script');
    script503.src = 'Bloques/Matematicas/map.js';
    head.appendChild(script503);

    const script504 = document.createElement('script');
    script504.src = 'Bloques/Matematicas/max.js';
    head.appendChild(script504);

    const script505 = document.createElement('script');
    script505.src = 'Bloques/Matematicas/min.js';
    head.appendChild(script505);
    
    const script506 = document.createElement('script');
    script506.src = 'Bloques/Matematicas/pow.js';
    head.appendChild(script506);
    
    const script507 = document.createElement('script');
    script507.src = 'Bloques/Matematicas/sq.js';
    head.appendChild(script507);

    const script508 = document.createElement('script');
    script508.src = 'Bloques/Matematicas/sqrt.js';
    head.appendChild(script508);

    const script509 = document.createElement('script');
    script509.src = 'Bloques/Matematicas/random.js';
    head.appendChild(script509);


    const script602 = document.createElement('script');
    script602.src = 'Bloques/LCD/lcd_clear.js';
    head.appendChild(script602);

    const script603 = document.createElement('script');
    script603.src = 'Bloques/LCD/lcd_home.js';
    head.appendChild(script603);

    const script604 = document.createElement('script');
    script604.src = 'Bloques/LCD/lcd_setCursor.js';
    head.appendChild(script604);

    const script605 = document.createElement('script');
    script605.src = 'Bloques/LCD/lcd_write.js';
    head.appendChild(script605);

    const script606 = document.createElement('script');
    script606.src = 'Bloques/LCD/lcd_print.js';
    head.appendChild(script606);

    const script607 = document.createElement('script');
    script607.src = 'Bloques/LCD/lcd_cursor.js';
    head.appendChild(script607);

    const script608 = document.createElement('script');
    script608.src = 'Bloques/LCD/lcd_noCursor.js';
    head.appendChild(script608);

    const script609 = document.createElement('script');
    script609.src = 'Bloques/LCD/lcd_blink.js';
    head.appendChild(script609);

    const script610 = document.createElement('script');
    script610.src = 'Bloques/LCD/lcd_noBlink.js';
    head.appendChild(script610);

    const script611 = document.createElement('script');
    script611.src = 'Bloques/LCD/lcd_display.js';
    head.appendChild(script611);

    const script612 = document.createElement('script');
    script612.src = 'Bloques/LCD/lcd_noDisplay.js';
    head.appendChild(script612);

    const script613 = document.createElement('script');
    script613.src = 'Bloques/LCD/lcd_scrollLeft.js';
    head.appendChild(script613);

    const script614 = document.createElement('script');
    script614.src = 'Bloques/LCD/lcd_scrollRight.js';
    head.appendChild(script614);

    const script615 = document.createElement('script');
    script615.src = 'Bloques/LCD/lcd_autoscroll.js';
    head.appendChild(script615);

    const script616 = document.createElement('script');
    script616.src = 'Bloques/LCD/lcd_noAutoscroll.js';
    head.appendChild(script616);

    const script617 = document.createElement('script');
    script617.src = 'Bloques/LCD/lcd_leftRight.js';
    head.appendChild(script617);

    const script618 = document.createElement('script');
    script618.src = 'Bloques/LCD/lcd_rightLeft.js';
    head.appendChild(script618);

    const script619 = document.createElement('script');
    script619.src = 'Bloques/Teclado/Teclado_include.js';
    head.appendChild(script619);

    const script620 = document.createElement('script');
    script620.src = 'Bloques/Teclado/Teclado_read.js';
    head.appendChild(script620);

    const script621 = document.createElement('script');
    script621.src = 'Bloques/Teclado/Teclado_val.js';
    head.appendChild(script621);



    const script701 = document.createElement('script');
    script701.src = 'Bloques/Digital/digitalRead.js';
    head.appendChild(script701);

    const script702 = document.createElement('script');
    script702.src = 'Bloques/Digital/digitalWrite.js';
    head.appendChild(script702);


    const script801 = document.createElement('script');
    script801.src = 'Bloques/Analogico/analogRead.js';
    head.appendChild(script801);

    const script802 = document.createElement('script');
    script802.src = 'Bloques/Analogico/analogReference.js';
    head.appendChild(script802);

    const script803 = document.createElement('script');
    script803.src = 'Bloques/Analogico/analogWrite.js';
    head.appendChild(script803);

    
    const script901 = document.createElement('script');
    script901.src = 'Bloques/Tiempo/delay.js';
    head.appendChild(script901);
    
    const script902 = document.createElement('script');
    script902.src = 'Bloques/Tiempo/delayMicroseconds.js';
    head.appendChild(script902);

    const script903 = document.createElement('script');
    script903.src = 'Bloques/Tiempo/micros.js';
    head.appendChild(script903);

    const script904 = document.createElement('script');
    script904.src = 'Bloques/Tiempo/milis.js';
    head.appendChild(script904);



    const script1001 = document.createElement('script');
    script1001.src = 'Bloques/Serial/serial_available.js';
    head.appendChild(script1001);

    const script1002 = document.createElement('script');
    script1002.src = 'Bloques/Serial/serial_read.js';
    head.appendChild(script1002);

    const script1003 = document.createElement('script');
    script1003.src = 'Bloques/Serial/serial_write.js';
    head.appendChild(script1003);

    const script1004 = document.createElement('script');
    script1004.src = 'Bloques/Serial/serial_print.js';
    head.appendChild(script1004);
    
    const script1005 = document.createElement('script');
    script1005.src = 'Bloques/Serial/serial_println.js';
    head.appendChild(script1005);
    
    const script1006 = document.createElement('script');
    script1006.src = 'Bloques/Serial/serial_flush.js';
    head.appendChild(script1006);

    const script1007 = document.createElement('script');
    script1007.src = 'Bloques/Serial/serial_end.js';
    head.appendChild(script1007);


    const script1101= document.createElement('script');
    script1101.src = 'Bloques/Sensores/HC04_begin.js';
    head.appendChild(script1101);

    const script1102= document.createElement('script');
    script1102.src = 'Bloques/Sensores/HC04_loop.js';
    head.appendChild(script1102);

    const script1103= document.createElement('script');
    script1103.src = 'Bloques/Sensores/HC04_include.js';
    head.appendChild(script1103);

    const script1104= document.createElement('script');
    script1104.src = 'Bloques/Sensores/HC04_dist.js';
    head.appendChild(script1104);

    const script1105= document.createElement('script');
    script1105.src = 'Bloques/Sensores/LDR_loop.js';
    head.appendChild(script1105);

    const script1106= document.createElement('script');
    script1106.src = 'Bloques/Sensores/LDR_value.js';
    head.appendChild(script1106);
    
    const script1107= document.createElement('script');
    script1107.src = 'Bloques/Sensores/LDR_include.js';
    head.appendChild(script1107);

    const script1200 = document.createElement('script');
    script1200.src = 'Bloques/Funciones/function.js';
    head.appendChild(script1200);

    const script1201 = document.createElement('script');
    script1201.src = 'Bloques/Funciones/interruption.js';
    head.appendChild(script1201);

    const script6901 = document.createElement('script');
    script6901.src = 'Bloques/Funciones/simple_text.js';
    head.appendChild(script6901);

    const script6902 = document.createElement('script');
    script6902.src = 'Bloques/Funciones/group_block.js';
    head.appendChild(script6902);
  }
  
  loadScripts();