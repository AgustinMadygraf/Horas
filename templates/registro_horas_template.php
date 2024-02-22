<!--registro_horas_template.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Registro de Horas</title>
    <link rel="stylesheet" type="text/css" href="CSS/header.css">
    <link rel="stylesheet" type="text/css" href="CSS/table_styles.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <?php if ($resultado->num_rows > 0): ?>
        <table border='1'>
            <thead>
                <tr>
                    <th>Legajo</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Fecha</th>
                    <th>Día</th>
                    <th>Horas</th>
                    <th>Centro de costo</th>
                    <th>Proceso</th>
                </tr>
            </thead>
            <tbody>
                <?php while($fila = $resultado->fetch_assoc()): ?>
                    <?php 
                    $fechaObj = DateTime::createFromFormat('Y-m-d', $fila["fecha"]);
                    $fechaFormateada = $fechaObj ? $fechaObj->format('d/m/Y') : 'Fecha inválida';
                    $dia = date('l', strtotime($fila["fecha"]));
                    $diaEnEspañol = traducirDia($dia);
                    ?>
                    <tr>
                        <td><?= $fila["legajo"] ?></td>
                        <td><?= $fila["nombre"] ?></td>
                        <td><?= $fila["apellido"] ?></td>
                        <td><?= $fechaFormateada ?></td>
                        <td><?= $diaEnEspañol ?></td>
                        <td><?= $fila["horas_trabajadas"] ?></td>
                        <td><?= obtenerNombreCentroCosto($fila["centro_costo"]) ?></td>
                        <td><?= $fila["proceso"] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No se encontraron resultados.</p>
    <?php endif; ?>

    <script>
        $(document).ready(function() {
            $('table').DataTable();
        });
    </script>
</body>
</html>
