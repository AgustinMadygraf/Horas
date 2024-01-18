<!DOCTYPE html>
<html>
<head>
    <title>Registro de Horas</title>
</head>
<body>
    <?php
    if ($resultado && $resultado->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Legajo</th>
                    <th>Fecha</th>
                    <th>Día</th>
                    <th>Horas</th>
                    <th>Centro de costo</th>
                    <th>Proceso</th>
                </tr>";
        while($fila = $resultado->fetch_assoc()) {
            // Convertir la fecha a día de la semana
            $dia = date('l', strtotime($fila["fecha"]));
            // Traducir el día al español
            $diasEnEspañol = [
                'Monday'    => 'Lunes',
                'Tuesday'   => 'Martes',
                'Wednesday' => 'Miércoles',
                'Thursday'  => 'Jueves',
                'Friday'    => 'Viernes',
                'Saturday'  => 'Sábado',
                'Sunday'    => 'Domingo',
            ];
            $diaEnEspañol = isset($diasEnEspañol[$dia]) ? $diasEnEspañol[$dia] : 'Desconocido';

            echo "<tr>
                    <td>".$fila["legajo"]."</td>
                    <td>".$fila["fecha"]."</td>  
                    <td>".$diaEnEspañol."</td>  
                    <td>".$fila["horas_trabajadas"]."</td>
                    <td>".obtenerNombreCentroCosto($fila["centro_costo"])."</td>
                    <td>".$fila["proceso"]."</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron resultados.";
    }
    ?>
</body>
</html>
