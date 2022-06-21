<!DOCTYPE html>
<?php
    $bbdd = mysqli_connect('localhost', 'admin', 'telemetria', 'sistema_de_telemetria') or
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
        
        <br>
            
        <h5>Fechas en las que se ha detectado un Incendio</h5></div>

        <div id="promedio" style="overflow-x:auto; padding-left: 10px; padding-right: 10px;">
            <table>     
                <tr>
                    <th>Año</th>
                    <th>Mes</th>
                    <th>Día</th>
                    <th>Hora</th>
                    <th>Minutos</th>
                    <th>Segundos</th>
                </tr>

                <?php 
                    //Enseña las últimas 25 muestras
                    $query = "SELECT anyo, mes, dia, hora, minutos, segundos FROM sensor_llama WHERE deteccion_llama=1 ORDER BY anyo DESC, mes DESC, dia DESC, hora DESC, minutos DESC, segundos DESC";
                    $result = mysqli_query($bbdd, $query);

                    $i = 0;
                    while(++$i < 24 && $row = mysqli_fetch_array($result, MYSQLI_NUM))
                    {      
                        print "<tr><td>{$row[0]}</td><td>{$row[1]}</td><td>{$row[2]}</td><td>{$row[3]}</td><td>{$row[4]}</td><td>{$row[5]}</td></tr>";
                    }  
                ?>
            </table>
        </div> 
    </body>
</html>
