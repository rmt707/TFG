<!DOCTYPE html>
<?php
    header("Refresh: 7");

    $bbdd = mysqli_connect('localhost', 'admin', 'telemetria', 'sistema_de_telemetria') or
        die("Error de conexión".mysqli_error($bbdd));

    mysqli_set_charset($bbdd, "utf8");

    //Lectura de valores de la BBDD
    $query = "SELECT humedad FROM humedad ORDER BY anyo DESC, mes DESC, dia DESC, hora DESC, minutos DESC, segundos DESC";
    $result = mysqli_query($bbdd, $query);
    $humedad = mysqli_fetch_array($result, MYSQLI_NUM);
    $humedad[0] = floatval($humedad[0]);

    $query = "SELECT temperatura FROM temperatura ORDER BY anyo DESC, mes DESC, dia DESC, hora DESC, minutos DESC, segundos DESC";
    $result = mysqli_query($bbdd, $query);
    $temperatura = mysqli_fetch_array($result, MYSQLI_NUM);
    $temperatura[0] = floatval($temperatura[0]);

    $query = "SELECT deteccion_llama FROM sensor_llama ORDER BY anyo DESC, mes DESC, dia DESC, hora DESC, minutos DESC, segundos DESC";
    $result = mysqli_query($bbdd, $query);
    $llama = mysqli_fetch_array($result, MYSQLI_NUM);
    $llama[0] = intval($llama[0]);

    $query = "SELECT deteccion_sonido FROM sensor_sonido ORDER BY anyo DESC, mes DESC, dia DESC, hora DESC, minutos DESC, segundos DESC";
    $result = mysqli_query($bbdd, $query);
    $sonido = mysqli_fetch_array($result, MYSQLI_NUM);
    $sonido[0] = intval($sonido[0]);
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Sistema de Telemetría</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="description" content="Aplicacion web de un Sistema de Telemetria"> <!--Descripción de la app-->
        <meta name="keywords" content="Technology"> <!--Palabras clave-->
        <meta name="viewport" content="width=device-width, initial-scale=0.6" /> <!--Válido para app móvil-->

        <link rel="stylesheet" href="main.css" type="text/css" charset="utf-8" />
    </head>
    
    <body>    
        <div id="title" style="text-align: center; font-size: 40px;"><h1>Sistema de Telemetría</h1></div>
        
        <nav id=menu>
            <ul>
                <li><a href="pages/deteccion_fuego.php">Detecciones de incendios</a></li>
                <li><a href="pages/info_mes.php">Info por mes</a></li>
                <li><a href="pages/info_dia.php">Info por día</a></li>
                <li><a href="pages/info_hora.php">Info por hora</a></li>
            </ul>
        </nav>
        
        <br><br>
        
        <div id="fuego">
            <h3>Detección de fuego: </h3>
            <?php
                if($llama[0] == '0') {
                    echo "<div style='width: 240px; background-color: lightgreen; margin-left: 0px; margin-bottom: 25px; text-align: center'>No se detecta fuego</div>";
                }
                else if($llama[0] == '1') {
                    echo "<div style='width: 240px; background-color: #FF886E; margin-left: 0px; margin-bottom: 25px; text-align: center'>Se detecta fuego</div>";
                }
            ?>
        </div>
        
        <div id="sonido">
            <h3>Detección de sonido: </h3>
            <?php
                if($sonido[0] == '0') {
                    echo "<div style='width: 100px; background-color: white; margin-left: 80px; text-align: center'>LED OFF</div>";
                }
                else if($sonido[0] == '1') {
                    echo "<div style='width: 100px; background-color: lightgreen; margin-left: 80px; text-align: center'>LED ON</div>";
                }
            ?>
        </div>
        
        <br><br><br><br><br><br><br><br><br>
        
        <div id="humtemp">
            <table class="default">
                <tr>
                    <th class = "encab_listado">Humedad (%)</th>
                    <th class = "encab_listado">Temperatura (ºC)</th>
                </tr>
        
                <tr>
                    <td class="celda_listado"><?php echo($humedad[0]); ?></td>
				    <td class="celda_listado"><?php echo($temperatura[0]); ?></td>
		        </tr>
            </table>
        </div>
    </body>
</html>
