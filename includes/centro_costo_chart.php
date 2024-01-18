<!DOCTYPE html>
<html>
<head>
    <title>Total Horas por Centro de Costo</title>
    <link rel="stylesheet" type="text/css" href="path/to/your/main_stylesheet.css"> <!-- Asegúrate de enlazar tu hoja de estilos principal aquí -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(function() {
            drawChart(<?php echo $datosJson; ?>);
        });
        function drawChart(dataArray) {
            var data = google.visualization.arrayToDataTable(dataArray);
            var options = {
                title: 'Total Horas por Centro de Costo',
                pieHole: 0.4, // Para un diseño de donut chart
                chartArea: { width: '100%', height: '80%' }, // Ajustar para responsividad
                // Otras opciones de estilo...
            };
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
