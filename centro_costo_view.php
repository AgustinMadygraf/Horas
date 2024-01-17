<!DOCTYPE html><html><head>
      <title>Total Horas por Centro de Costo</title>
      <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
      <script type='text/javascript'>
          google.charts.load('current', {'packages':['corechart']});
          google.charts.setOnLoadCallback(function() {
            drawChart(<?php echo $datosJson; ?>);
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
      </head><body>

      <table border="1">
        <tr>
            <th>Centro de Costo</th>
            <th>Horas</th>
            <th>Porcentaje [%]</th>
        </tr>
        <?php
        if ($resultado && $resultado->num_rows > 0) {
            $resultado->data_seek(0); // Rebobinar para usar de nuevo el resultado
            while ($fila = $resultado->fetch_assoc()) {
                $porcentaje = $fila["total_horas"] / $totalHoras * 100;
                
                echo "<tr>
                        <td>". obtenerNombreCentroCosto($fila["centro_costo"]). "</td>
                        <td>". $fila["total_horas"]. "</td>
                        <td>". number_format($porcentaje, 2). "%</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No se encontraron resultados.</td></tr>";
        }
        ?>
    </table>

    <!-- Div para el gráfico de Google Charts -->
    <div id="piechart" style="width: 900px; height: 500px;"></div>
</body>
</html>
