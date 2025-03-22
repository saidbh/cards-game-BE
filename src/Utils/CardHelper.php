<?php

namespace App\Utils;

class CardHelper
{
    public const COLOR_ORDER = [
        'Carreaux' => 1,
        'Cœur'     => 2,
        'Pique'    => 3,
        'Trèfle'   => 4,
    ];

    public const VALUE_ORDER = [
        'AS'    => 1,
        '2'     => 2,
        '3'     => 3,
        '4'     => 4,
        '5'     => 5,
        '6'     => 6,
        '7'     => 7,
        '8'     => 8,
        '9'     => 9,
        '10'    => 10,
        'Valet' => 11,
        'Dame'  => 12,
        'Roi'   => 13,
    ];
}
