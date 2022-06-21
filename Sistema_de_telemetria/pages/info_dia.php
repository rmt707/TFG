<!DOCTYPE html>
<?php
    $bbdd = mysqli_connect('localhost', 'root', '', 'sistema_de_telemetria') or
        die("Error de conexión".mysqli_error($bbdd));

    mysqli_set_charset($bbdd, "utf8");
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Sistema de Telemetría</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="description" content="Aplicacion web de un Sistema de Telemetria"> <!--Descripción de la app-->
        <meta name="keywords" content="Technology"> <!--Palabras clave-->
        <meta name="viewport" content="width=device-width, initial-scale=0.6" /> <!--Válido para app móvil-->

        <link rel="stylesheet" href="../sub_page.css" type="text/css" charset="utf-8" />
    </head>
    
    <body>    
        <div id="title" style="text-align: center; font-size: 32px;"><h1>Sistema de Telemetría</h1>
        
        <nav id=menu>
            <ul>
                <li><a href="../index.php">Página principal</a></li>
                <li><a href="deteccion_fuego.php">Detecciones de incendios</a></li>
                <li><a href="info_mes.php">Info por mes</a></li>
                <li><a href="info_dia.php">Info por día</a></li>
                <li><a href="info_hora.php">Info por hora</a></li>
            </ul>
        </nav>

        <div id="registro" style="overflow-x:auto; padding-left: 10px; padding-right: 10px;">
            <table>
                <h5>Información de Humedad (%) y Temperatura (ºC) por Día</h5>
                <tr>
                    <th colspan="3">Fecha</th>
                    <th colspan="3">Humedad</th>
                    <th colspan="3">Temperatura</th>
                </tr>
                <tr>
                    <th>Año</th>
                    <th>Mes</th>
                    <th>Día</th>
                    <th>Máx</th>
                    <th>Promedio</th>
                    <th>Mín</th>
                    <th>Máx</th>
                    <th>Promedio</th>
                    <th>Mín</th>
                </tr>

                <?php 
                    //Muestra el registro de los últimos 31 días
                    $query = "SELECT humedad.anyo, humedad.mes, humedad.dia, Max(humedad.humedad) AS MaxHumedad, round(Avg(humedad.humedad),2) AS PromHumedad, Min(humedad.humedad) AS MinHumedad, Max(temperatura.temperatura) AS MaxTemperatura, round(Avg(temperatura.temperatura),2) AS PromHumedad, Min(temperatura.temperatura) AS MinTemperatura FROM humedad INNER JOIN temperatura ON (humedad.dia = temperatura.dia) AND (humedad.mes = temperatura.mes) AND (humedad.anyo = temperatura.anyo) AND (humedad.hora = temperatura.hora) AND (humedad.minutos = temperatura.minutos) AND (humedad.segundos = temperatura.segundos) GROUP BY humedad.anyo, humedad.mes, humedad.dia ORDER BY humedad.anyo DESC, humedad.mes DESC, humedad.dia DESC";
                    $result = mysqli_query($bbdd, $query);

                    $i = 0;
                    while(++$i < 30 && $row = mysqli_fetch_array($result, MYSQLI_NUM))
                    {      
                        print "<tr><td>{$row[0]}</td><td>{$row[1]}</td><td>{$row[2]}</td><td>{$row[3]}</td><td>{$row[4]}</td><td>{$row[5]}</td><td>{$row[6]}</td><td>{$row[7]}</td><td>{$row[8]}</td></tr>";
                    }  
                ?>
            </table>
        </div> 
    </body>
</html>