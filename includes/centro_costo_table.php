
    <table border="1" class="responsive-table">
        <thead>
            <tr>
                <th>Centro de Costo</th>
                <th>Horas</th>
                <th>Porcentaje [%]</th>
            </tr>
        </thead>
        <tbody>
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
        </tbody>
    </table>
    <br><br>
    <div id="piechart" style="width: 100%; max-width: 900px; height: 500px;" role="img" aria-label="Gráfico circular mostrando las horas por centro de costo"></div>
</body>
</html>
