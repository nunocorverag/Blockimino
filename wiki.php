<?php
include("includes/header.php");
?>
</div>
<!DOCTYPE html>
<html>
<head>
    
    <title>Blockimino Help</title>
    <link rel="stylesheet" href="assets/css/wiki_style.css">


    <!-- NAV BAR -->
    <link rel="stylesheet" href="assets/css/wiki_nav_bar_style.css">

    <link rel="stylesheet" href="Libraries/nav-bar.css">


</head>
<body>
    <div id="search-container">
        <input type="text" id="search-input" placeholder="Prueba buscar una palabra..." />
        <button id="search-button">Buscar Siguiente</button>
    </div>

    
    <div id="nav">
    </div>
    
    <button id="scroll-to-top-btn">Regresar al indice</button>


    <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////indice -->
    <div class="contenido">
        <div class="indice">
            <h2>Índice</h2>
            <ul>
                <!-- Estructuras de control -->
                <li><a href="#seccion1">Estructuras de control</a>
                    <ul>
                        <li><a href="#subseccion1.1">for</a></li>
                        <li><a href="#subseccion1.2">while</a></li>
                        <li><a href="#subseccion1.3">do while</a></li>
                        <li><a href="#subseccion1.4">if</a></li>
                        <li><a href="#subseccion1.5">if else</a></li>
                        <li><a href="#subseccion1.6">switch</a></li>
                        <li><a href="#subseccion1.7">case</a></li>
                    </ul>
                </li>
                <!-- Estructuras de control -->
                <!-- variables -->
                <li><a href="#seccion2">Variables</a>
                    <ul>
                        <li><a href="#subseccion2.1">Crear variable</a></li>
                        <li><a href="#subseccion2.2">Usar variable</a></li>
                        <li><a href="#subseccion2.3">Asignar valor</a></li>
                    </ul>
                </li>
                <!-- variables -->
                <!-- operadores -->
                <li><a href="#seccion3">Operadores</a>
                    <ul>
                        <li><a href="#subseccion3.1">operadores aritmeticos</a></li>
                        <li><a href="#subseccion3.2">operadores booleanos</a></li>
                        <li><a href="#subseccion3.3">operadores de comparacion</a></li>
                        <li><a href="#subseccion3.3">operador de asignacion</a></li>
                    </ul>
                </li>
                <!-- operadores -->
                <!-- matematicas -->
                <li><a href="#seccion4">Matematicas</a>
                    <ul>
                        <li><a href="#subseccion4.1">abs</a></li>
                        <li><a href="#subseccion4.2">constraint</a></li>
                        <li><a href="#subseccion4.3">map</a></li>
                        <li><a href="#subseccion4.4">max</a></li>
                        <li><a href="#subseccion4.5">min</a></li>
                        <li><a href="#subseccion4.6">pow</a></li>
                        <li><a href="#subseccion4.7">sq</a></li>
                        <li><a href="#subseccion4.8">sqrt</a></li>
                        <li><a href="#subseccion4.9">random</a></li>
                    </ul>
                </li>
                <!-- matematicas -->
                <!-- digital -->
                <li><a href="#seccion5">Digital</a>
                    <ul>
                        <li><a href="#subseccion5.1">digital read</a></li>
                        <li><a href="#subseccion5.2">digital write</a></li>
                    </ul>
                </li>
                <!-- digital -->
                <!-- analogico -->
                <li><a href="#seccion6">Analogico</a>
                    <ul>
                        <li><a href="#subseccion6.1">analog read</a></li>
                        <li><a href="#subseccion6.2">analog write</a></li>
                    </ul>
                </li>
                <!-- analogico -->
                <!-- funciones -->
                <li><a href="#seccion7">Funciones</a>
                    <ul>
                        <li><a href="#subseccion7.1">Crear funcion</a></li>
                        <li><a href="#subseccion7.2">Crear funcion void</a></li>
                        <li><a href="#subseccion7.3">Llamar funcion</a></li>
                        <li><a href="#subseccion7.4">Usar funcion como parametro</a></li>
                        <li><a href="#subseccion7.5">Crear interrupcion</a></li>
                    </ul>
                </li>
                <!-- funciones -->
                <!-- tiempo -->
                <li><a href="#seccion8">Tiempo</a>
                    <ul>
                        <li><a href="#subseccion8.1">delay</a></li>
                        <li><a href="#subseccion8.2">delay Microsegundos</a></li>
                        <li><a href="#subseccion8.3">milis</a></li>
                        <li><a href="#subseccion8.4">micros</a></li>
                    </ul>
                </li>
                <!-- tiempo -->
                <!-- serial -->
                <li><a href="#seccion9">Puerto Serial</a>
                    <ul>
                        <li><a href="#subseccion13.8">Serial.begin</a></li>
                        <li><a href="#subseccion9.1">Serial.available</a></li>
                        <li><a href="#subseccion9.2">Serial.read</a></li>
                        <li><a href="#subseccion9.3">Serial.write</a></li>
                        <li><a href="#subseccion9.4">Serial.print</a></li>
                        <li><a href="#subseccion9.5">Serial.println</a></li>
                        <li><a href="#subseccion9.6">Serial.flush</a></li>
                        <li><a href="#subseccion9.7">Serial.end</a></li>
                    </ul>
                </li>
                <!-- serial -->
                <!-- LCD -->
                <li><a href="#seccion10">Pantalla LCD</a>
                    <ul>
                        <li><a href="#subseccion13.2">include liquid cristal (LCD)</a></li>
                        <li><a href="#subseccion13.9">lcd.begin</a></li>
                        <li><a href="#subseccion10.1">lcd.clear</a></li>
                        <li><a href="#subseccion10.2">lcd.home</a></li>
                        <li><a href="#subseccion10.3">lcd.setCursor</a></li>
                        <li><a href="#subseccion10.4">lcd.write</a></li>
                        <li><a href="#subseccion10.5">lcd.print</a></li>
                        <li><a href="#subseccion10.6">controles basicos de la pantalla LCD</a></li>
                        
                    </ul>
                </li>
                <!-- LCD -->
                <!-- teclado -->
                <li><a href="#seccion11">Teclado matricial</a>
                    <ul>
                        <li><a href="#subseccion13.3">include teclado matricial</a></li>
                        <li><a href="#subseccion11.1">Obtener valor</a></li>
                        <li><a href="#subseccion11.2">Valor del teclado</a></li>
                    </ul>
                </li>
                <!-- teclado -->
                <!-- sensores -->
                <li><a href="#seccion12">Sensores</a>
                    <ul>
                        <li><a href="#subseccion13.4">Definir pines del sensor HC-04</a></li>
                        <li><a href="#subseccion13.7">Configurar sensor de distancia HC-04</a></li>
                        <li><a href="#subseccion12.1">Valor de distancia</a></li>
                        <li><a href="#subseccion12.2">Leer distancia</a></li>
                        <li><a href="#subseccion13.5">Definir pines de luminosidad LDR</a></li>
                        <li><a href="#subseccion12.3">Valor de luminosidad</a></li>
                        <li><a href="#subseccion12.4">Leer luminosidad</a></li>
                    </ul>
                </li>
                <!-- sensores -->
                <!-- Preparaciones -->
                <li><a href="#seccion13">Preparaciones</a>
                    <ul>
                        <li><a href="#subseccion13.1">include</a></li>
                        
                        
                        
                        
                        <li><a href="#subseccion13.6">pinmode</a></li>
                        
                        
                        
                        <li><a href="#subseccion13.10">define</a></li>
                        
                        <li><a href="#subseccion13.12">analogReference</a></li>
                    </ul>
                </li>
                <!-- Preparaciones -->
                
            </ul>
        </div>

        <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////contenido -->
        <!-- Estructuras de control -->
        <div class="seccion" id="seccion1">
            <h1>Estructuras de control</h1>
            <div class="subseccion" id="subseccion1.1">
                <h2>for</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/EstructurasDeControl/for/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/EstructurasDeControl/for/imagen.jpg' style='width:100%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/EstructurasDeControl/for/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/on4JpUy-1mw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion1.2">
                <h2>while</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/EstructurasDeControl/while/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/EstructurasDeControl/while/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/EstructurasDeControl/while/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/ra4ToRPP7v8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion1.3">
                <h2>do while</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/EstructurasDeControl/doWhile/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/EstructurasDeControl/doWhile/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/EstructurasDeControl/doWhile/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/k_NJnnBQZy0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion1.4">
                <h2>if</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/EstructurasDeControl/if/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/EstructurasDeControl/if/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/EstructurasDeControl/if/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/_7OkRiwL01U" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion1.5">
                <h2>if else</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/EstructurasDeControl/ifElse/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/EstructurasDeControl/ifElse/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/EstructurasDeControl/ifElse/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/6LeVcsTeGq8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion1.6">
                <h2>switch</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/EstructurasDeControl/switch/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/EstructurasDeControl/switch/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/EstructurasDeControl/switch/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/g-huN_qb1C4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion1.7">
                <h2>case</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/EstructurasDeControl/case/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/EstructurasDeControl/case/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/EstructurasDeControl/case/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/jk5OrSPjiM8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
        </div>
        <!-- Estructuras de control -->

        <!-- Variables -->
        <div class="seccion" id="seccion2">
            <h1>Variables</h1>
            <div class="subseccion" id="subseccion2.1">
                <h2>Crear variable</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Variables/crearVariables/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Variables/crearVariables/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Variables/crearVariables/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/Xz5-NcmcvC0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion2.2">
                <h2>Usar variable</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Variables/usarVariables/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Variables/usarVariables/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Variables/usarVariables/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/3FvWwOqUzto" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion2.3">
                <h2>Asignar valor</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Variables/asignarValoresVariables/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Variables/asignarValoresVariables/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Variables/asignarValoresVariables/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/3FvWwOqUzto" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
        </div>
        <!-- Variables -->

        <!-- Operadores -->
        <div class="seccion" id="seccion3">
            <h1>Operadores</h1>
            <div class="subseccion" id="subseccion3.1">
                <h2>operadores aritméticos</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Operadores/operadorAritmetico/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Operadores/operadorAritmetico/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Operadores/operadorAritmetico/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/FTMp88FZb7Y" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion3.2">
                <h2>operadores booleanos</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Operadores/operadorBooleano/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Operadores/operadorBooleano/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Operadores/operadorBooleano/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/tybdfWMTtOE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion3.3">
                <h2>operadores de comparación</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Operadores/operadorComparacion/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Operadores/operadorComparacion/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Operadores/operadorComparacion/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/6JrBMl219VI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion3.4">
                <h2>operador de asignación</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Operadores/operadorAsignacion/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Operadores/operadorAsignacion/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Operadores/operadorAsignacion/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/mVXSDqX6hqg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
        </div>
        <!-- Operadores -->

        <!-- Matematicas -->
        <div class="seccion" id="seccion4">
            <h1>Matematicas</h1>
            <div class="subseccion" id="subseccion4.1">
                <h2>abs</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Matematicas/abs/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Matematicas/abs/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Matematicas/abs/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/ODl8MyyuvIA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion4.2">
                <h2>constraint</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Matematicas/constraint/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Matematicas/constraint/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Matematicas/constraint/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/FVDQThX96q4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion4.3">
                <h2>map</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Matematicas/map/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Matematicas/map/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Matematicas/map/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/mj_1K3YoT5A" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion4.4">
                <h2>max</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Matematicas/max/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Matematicas/max/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Matematicas/max/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/V3RVM4-rESU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion4.5">
                <h2>min</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Matematicas/min/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Matematicas/min/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Matematicas/min/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/O0LOkw1k820" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion4.6">
                <h2>pow</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Matematicas/pow/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Matematicas/pow/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Matematicas/pow/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/BLQml5s8-10" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion4.7">
                <h2>sq</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Matematicas/sq/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Matematicas/sq/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Matematicas/sq/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/dJlDC8c64pI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion4.8">
                <h2>sqrt</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Matematicas/sqrt/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Matematicas/sqrt/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Matematicas/sqrt/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/zN0OrLWChXk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion4.9">
                <h2>random</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Matematicas/random/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Matematicas/random/imagen.jpg' style='width:70%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Matematicas/random/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/Tm9DgOYBQNI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
        </div>
        <!-- Matematicas -->

        <!-- digital -->
        <div class="seccion" id="seccion5">
            <h1>Digital</h1>
            <div class="subseccion" id="subseccion5.1">
                <h2>digital read</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Digital/digitalRead/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Digital/digitalRead/imagen.jpg' style='width:70%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Digital/digitalRead/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/SN1ANIOe_bg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion5.2">
                <h2>digital write</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Digital/digitalWrite/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Digital/digitalWrite/imagen.jpg' style='width:70%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Digital/digitalWrite/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/MJU4j5MsgKs" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
        </div>
        <!-- digital -->

        <!-- Analogico -->
        <div class="seccion" id="seccion6">
            <h1>Analogico</h1>
            <div class="subseccion" id="subseccion6.1">
                <h2>analog read</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Analogico/analogRead/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Analogico/analogRead/imagen.jpg' style='width:70%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Analogico/analogRead/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/rXSgMpU-tws" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion6.2">
                <h2>analog write</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Analogico/analogWrite/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Analogico/analogWrite/imagen.jpg' style='width:70%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Analogico/analogWrite/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/aA5ZpFySimw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
        </div>
        <!-- Analogico -->

        <!-- Funciones -->
        <div class="seccion" id="seccion7">
            <h1>Funciones</h1>
            <div class="subseccion" id="subseccion7.1">
                <h2>Crear funcion</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Funciones/funcion/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Funciones/funcion/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Funciones/funcion/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/lQNCwEGOUUE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion7.2">
                <h2>Crear funcion void</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Funciones/funcionVoid/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Funciones/funcionVoid/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Funciones/funcionVoid/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/DTW5Kdmq2ZQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion7.3">
                <h2>Llamar funcion</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Funciones/llamarFuncion/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Funciones/llamarFuncion/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Funciones/llamarFuncion/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/BRXKbAa8UKU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion7.4">
                <h2>Usar funcion como parametro</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Funciones/usarFuncion/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Funciones/usarFuncion/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Funciones/usarFuncion/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/BRXKbAa8UKU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion7.5">
                <h2>Crear interrupcion</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Funciones/interrupcion/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Funciones/interrupcion/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Funciones/interrupcion/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/pZaYKm6zcw0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
        </div>
        <!-- Funciones -->

        <!-- Tiempo -->
        <div class="seccion" id="seccion8">
            <h1>Tiempo</h1>
            <div class="subseccion" id="subseccion8.1">
                <h2>delay</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Tiempo/delay/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Tiempo/delay/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Tiempo/delay/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/D_-pNwe8-5E" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion8.2">
                <h2>delay Microsegundos</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Tiempo/delayM/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Tiempo/delayM/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Tiempo/delayM/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/WVYjLfZO_NU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion8.3">
                <h2>milis</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Tiempo/milis/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Tiempo/milis/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Tiempo/milis/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/XWXWT5DMMiA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion8.4">
                <h2>micros</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Tiempo/micros/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Tiempo/micros/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Tiempo/micros/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/Ae_aK1El304" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
        </div>
        <!-- Tiempo -->

        <!-- Puerto Serial -->
        <div class="seccion" id="seccion9">
            <h1>Puerto Serial</h1>
            <div class="subseccion" id="subseccion13.8">
                <h2>Serial.begin</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Serial/serialBegin/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Serial/serialBegin/imagen.jpg' style='width:70%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Serial/serialBegin/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/Sn80z1H8bi8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion9.1">
                <h2>Serial.available</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Serial/available/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Serial/available/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Serial/available/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <<iframe width="560" height="315" src="https://www.youtube.com/embed/_0KUhPQhtwA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion9.2">
                <h2>Serial.read</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Serial/read/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Serial/read/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Serial/read/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/gQJg5itR2Js" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion9.3">
                <h2>Serial.write</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Serial/write/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Serial/write/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Serial/write/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/CWprc3w6PQE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion9.4">
                <h2>Serial.print</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Serial/print/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Serial/print/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Serial/print/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                           <iframe width="560" height="315" src="https://www.youtube.com/embed/IOs6_HSr6kQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion9.5">
                <h2>Serial.println</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Serial/println/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Serial/println/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Serial/println/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/99hNRi70-JA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion9.6">
                <h2>Serial.flush</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Serial/flush/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Serial/flush/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Serial/flush/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/iPrxsJ1cmKU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion9.7">
                <h2>Serial.end</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Serial/end/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Serial/end/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Serial/end/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                           <iframe width="560" height="315" src="https://www.youtube.com/embed/_jQj0Y04TX0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
        </div>
        <!-- Puerto Serial -->

        <!-- Pantalla LCD -->
        <div class="seccion" id="seccion10">
            <h1>Pantalla LCD</h1>
            
            <div class="subseccion" id="subseccion13.2">
                <h2>include liquid cristal</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/LCD/includeLCD/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/LCD/includeLCD/imagen.jpg' style='width:100%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/LCD/includeLCD/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/CdMoqru9ySw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion13.9">
                <h2>lcd.begin</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/LCD/lcdBegin/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/LCD/lcdBegin/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/LCD/lcdBegin/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/u2c5-TMQWuM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion10.1">
                <h2>lcd.clear</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/LCD/clear/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/LCD/clear/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/LCD/clear/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/JEZiHQY-JPI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion10.2">
                <h2>lcd.home</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/LCD/home/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/LCD/home/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/LCD/home/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/JEZiHQY-JPI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion10.3">
                <h2>lcd.setCursor</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/LCD/setCursor/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/LCD/setCursor/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/LCD/setCursor/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/Xc-gq1Hatlg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion10.4">
                <h2>lcd.write</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/LCD/write/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/LCD/write/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/LCD/write/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/EAeuxjtkumM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion10.5">
                <h2>lcd.print</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/LCD/print/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/LCD/print/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/LCD/print/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/IOs6_HSr6kQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion10.6">
                <h2>Controles basicos de pantalla LCD</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/LCD/controles/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/LCD/controles/imagen.jpg' style='width:100%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/LCD/controles/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/JEZiHQY-JPI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
        </div>
        <!-- Pantalla LCD -->

        <!-- Teclado matricial -->
        <div class="seccion" id="seccion11">
            <h1>Teclado matricial</h1>
            <div class="subseccion" id="subseccion13.3">
                <h2>include teclado matricial</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Teclado/includeTeclado/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Teclado/includeTeclado/imagen.jpg' style='width:100%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Teclado/includeTeclado/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/4eq6OxBQI1w" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion11.1">
                <h2>Obtener valor</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Teclado/obtenerValor/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Teclado/obtenerValor/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Teclado/obtenerValor/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/c3BMiyeGTcA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion11.2">
                <h2>Valor del teclado</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Teclado/valorTeclado/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Teclado/valorTeclado/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Teclado/valorTeclado/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/c3BMiyeGTcA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
        </div>
        <!-- Teclado matricial -->

        <!-- Sensores -->
        <div class="seccion" id="seccion12">
            <h1>Sensores</h1>
            <div class="subseccion" id="subseccion13.4">
                <h2>Definir pines del sensor HC-04</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Sensores/pinesUltrasonido/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Sensores/pinesUltrasonido/imagen.jpg' style='width:100%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Sensores/pinesUltrasonido/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/xFZCpR-5xg4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion13.7">
                <h2>Configurar sensor de distancia HC-04</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Sensores/configurarSensorDistancia/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Sensores/configurarSensorDistancia/imagen.jpg' style='width:60%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Sensores/configurarSensorDistancia/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/FbBySM0M_c0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion12.1">
                <h2>Valor de distancia</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Sensores/valorDistancia/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Sensores/valorDistancia/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Sensores/valorDistancia/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/IF1eN0WK3bU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion12.2">
                <h2>Leer distancia</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Sensores/leerDistancia/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Sensores/leerDistancia/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Sensores/leerDistancia/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/IF1eN0WK3bU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion13.5">
                <h2>Definir pines de luminosidad LDR</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Sensores/pinesLuminosidad/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Sensores/pinesLuminosidad/imagen.jpg' style='width:70%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Sensores/pinesLuminosidad/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/d3zcjfjqFxE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion12.3">
                <h2>Valor de luminosidad</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Sensores/valorLuminosidad/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Sensores/valorLuminosidad/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Sensores/valorLuminosidad/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/h9wGZssIBOM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion12.4">
                <h2>Leer luminosidad</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Sensores/leerLuminosidad/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Sensores/leerLuminosidad/imagen.jpg' style='width:40%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Sensores/leerLuminosidad/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/h9wGZssIBOM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
        </div>
        <!-- Sensores -->

        <!-- Preparaciones -->
        <div class="seccion" id="seccion13">
            <h1>Preparaciones</h1>
            <div class="subseccion" id="subseccion13.10">
                <h2>define</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Preparaciones/define/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Preparaciones/define/imagen.jpg' style='width:100%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Preparaciones/define/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/Kw8Bf7uie-0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>

            <div class="subseccion" id="subseccion13.6">
                <h2>pinmode</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Preparaciones/pinmode/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Preparaciones/pinmode/imagen.jpg' style='width:100%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Preparaciones/pinmode/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/MJU4j5MsgKs" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>

            <div class="subseccion" id="subseccion13.12">
                <h2>analogReference</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Preparaciones/analogReference/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Preparaciones/analogReference/imagen.jpg' style='width:100%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Preparaciones/analogReference/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/W-f2IOLV10g" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
            <div class="subseccion" id="subseccion13.1">
                <h2>include</h2>
                <table>
                    <tr><td> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Preparaciones/include/tema.txt")); ?></p> </td>
                        <td> <img src='ContenidoDeLaWiki/Preparaciones/include/imagen.jpg' style='width:70%; height:auto;' /> </td></tr>
                    <tr><td colspan="2"> <p><?php echo nl2br(file_get_contents("ContenidoDeLaWiki/Preparaciones/include/descripcion.txt")); ?></p> </td></tr>
                    <tr><td colspan="2"> <p>Informacion adicional:</p>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/iaJlzTPtrd0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </td></tr>
                </table>
            </div>
        </div>
        
        <!-- Preparaciones -->




    </div>
    <script src="BotonRegresarIndice.js"></script>
    <script src="IndiceLento.js"></script>
    <script src="Buscador.js"></script>



</body>
</html>