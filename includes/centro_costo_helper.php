<?php
// centro_costo_helper.php

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
