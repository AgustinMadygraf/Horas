<?php
include 'templates/header.php'; 
require_once 'includes/db.php';

// Preparar la consulta SQL
$sql = "SELECT COALESCE(centro_costo, 'Sin Asignar') AS centro_costo, SUM(horas_trabajadas) AS total_horas FROM registro_horas_trabajo GROUP BY COALESCE(centro_costo, 'Sin Asignar') ORDER BY `total_horas` DESC";
$resultado = $conexion->query($sql);

function obtenerNombreCentroCosto($codigo) {
    $nombresCentroCosto = [
        '1' => 'Maquina de bolsas',
        '2' => 'Boletas y folletería',
        '3' => 'Logistica',
        '4' => 'Administración',
        '5' => 'Club',
        '6' => 'Mantenimiento',
        '7' => 'Comedor',
        '8' => 'Guardia',
        '9' => 'Sistemas',
        '10' => 'Enfermería',

    ];
    return isset($nombresCentroCosto[$codigo]) ? $nombresCentroCosto[$codigo] : 'Desconocido';
}

$totalHoras = 0;
$datosGrafico = [["Centro de Costo", "Horas"]];

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $totalHoras += $fila["total_horas"];
        $nombreCentro = obtenerNombreCentroCosto($fila["centro_costo"]);
        array_push($datosGrafico, [$nombreCentro, (float)$fila["total_horas"]]);
    }
}

$datosJson = json_encode($datosGrafico);

// Comenzar el HTML
echo "<!DOCTYPE html><html><head>
      <title>Total Horas por Centro de Costo</title>
      <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
      <script type='text/javascript'>
          google.charts.load('current', {'packages':['corechart']});
          google.charts.setOnLoadCallback(function() {
              drawChart($datosJson);
          });
          function drawChart(dataArray) {
              var data = google.visualization.arrayToDataTable(dataArray);
              var options = {
                  title: 'Total Horas por Centro de Costo'
              };
              var chart = new google.visualization.PieChart(document.getElementById('piechart'));
              chart.draw(data, options);
          }
      </script>
      </head><body>";

// Mostrar tabla de datos
echo "<table border='1'>
      <tr>
          <th>Centro de Costo</th>
          <th>Horas</th>
          <th>Porcentaje [%]</th>
      </tr>";

$resultado->data_seek(0); // Rebobinar para usar de nuevo el resultado

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $porcentaje = $fila["total_horas"] / $totalHoras * 100;
        echo "<tr>
                <td>". obtenerNombreCentroCosto($fila["centro_costo"]). "</td>
                <td>". $fila["total_horas"]. "</td>
                <td>". number_format($porcentaje, 2). "%</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}

echo "<div id='piechart' style='width: 900px; height: 500px;'></div>";
echo "</body></html>";
$conexion->close();
?>
