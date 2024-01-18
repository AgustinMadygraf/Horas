<?php // registro_horas_template.php ?>
<!DOCTYPE html>
<html>
<head>
    <title>Registro de Horas</title>
</head>
<body>
    <?php if ($resultado->num_rows > 0): ?>
        <table border='1'>
            <tr>
                <th>Legajo</th>
                <th>Fecha</th>
                <th>Día</th>
                <th>Horas</th>
                <th>Centro de costo</th>
                <th>Proceso</th>
            </tr>
            <?php while($fila = $resultado->fetch_assoc()): ?>
                <?php 
                $dia = date('l', strtotime($fila["fecha"]));
                $diaEnEspañol = $diasEnEspañol[$dia] ?? 'Desconocido';
                ?>
                <tr>
                    <td><?= $fila["legajo"] ?></td>
                    <td><?= $fila["fecha"] ?></td>
                    <td><?= $diaEnEspañol ?></td>
                    <td><?= $fila["horas_trabajadas"] ?></td>
                    <td><?= obtenerNombreCentroCosto($fila["centro_costo"]) ?></td>
                    <td><?= $fila["proceso"] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No se encontraron resultados.</p>
    <?php endif; ?>
</body>
</html>
