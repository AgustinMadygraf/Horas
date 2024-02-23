<?php 
//header.php

$legajo = isset($_GET['legajo']) ? $_GET['legajo'] : '';



echo '<!DOCTYPE html><html><head>     <meta charset="UTF-8"> <link rel="stylesheet" type="text/css" href="CSS/index.css"> <link rel="stylesheet" type="text/css" href="CSS/header.css"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"> <link rel="icon" href="/imagenes/favicon.ico" type="image/x-icon"> <title>Registro de Horas</title></head><body>';
echo "<header> <br><br><br>    <div class='topnav'> <ul>";
echo "<li><a href='index.php' >Inicio</a></li>";
echo "<li><a href='insertar_centro.php?legajo=".$legajo."'>Insertar</a></li>";
echo "<li><a href='mostrar_horas.php?legajo=".$legajo."'>Visualizar</a></li>";
echo "<li><a href='modificar_centro.php?legajo=".$legajo."'>Modificar</a></li>";
echo "<li><a href='centro_costo.php'>Centro de Costos</a></li>";
echo "<li><a href='/horas/index.php' ".($paginaActual == '/horas/index.php' ? $claseActiva : "").">Horas</a></li>";
echo "<li><a href='/DigiRail/index.php' ".($paginaActual == '/DigiRail/index.php' ? $claseActiva : "").">DigiRail</a></li>";
echo "<li><a href='/Bolsas/index.php' ".($paginaActual == '/Bolsas/index.php' ? $claseActiva : "").">Costos Bolsas</a></li>";
echo "<li><a href='/phpMyAdmin/' target='_blank'>PHP MyAdmin</a></li>";
echo "</ul></div></header>"
?>