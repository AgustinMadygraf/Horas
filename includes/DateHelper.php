<?php
// DateHelper.php

function traducirDia($dia) {
    $diasEnEspañol = [
        'Monday'    => 'Lunes',
        'Tuesday'   => 'Martes',
        'Wednesday' => 'Miércoles',
        'Thursday'  => 'Jueves',
        'Friday'    => 'Viernes',
        'Saturday'  => 'Sábado',
        'Sunday'    => 'Domingo',
    ];

    return $diasEnEspañol[$dia] ?? 'Desconocido';
}
