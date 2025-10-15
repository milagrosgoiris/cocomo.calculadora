<?php

return [
    'modes' => [
        'organic' => ['a' => 3.2, 'b' => 1.05, 'c' => 2.5, 'd' => 0.38, 'label' => 'Orgánico'],
        'semi'    => ['a' => 3.0, 'b' => 1.12, 'c' => 2.5, 'd' => 0.35, 'label' => 'Semiacoplado'],
        'embed'   => ['a' => 2.8, 'b' => 1.20, 'c' => 2.5, 'd' => 0.32, 'label' => 'Empotrado'],
    ],

    'drivers' => [
        'RELY' => ['Muy Bajo'=>0.75,'Bajo'=>0.86,'Nominal'=>1.00,'Alto'=>1.15,'Muy Alto'=>1.40],
        'DATA' => ['Muy Bajo'=>null,'Bajo'=>0.94,'Nominal'=>1.00,'Alto'=>1.08,'Muy Alto'=>1.16],
        'CPLX' => ['Muy Bajo'=>0.70,'Bajo'=>0.85,'Nominal'=>1.00,'Alto'=>1.15,'Muy Alto'=>1.30,'Extra Alto'=>1.65],

        'TIME' => ['Nominal'=>1.00,'Alto'=>1.11,'Muy Alto'=>1.30,'Extra Alto'=>1.66],
        'STOR' => ['Nominal'=>1.00,'Alto'=>1.06,'Muy Alto'=>1.21,'Extra Alto'=>1.56],
        'VIRT' => ['Muy Bajo'=>null,'Bajo'=>0.87,'Nominal'=>1.00,'Alto'=>1.15,'Muy Alto'=>1.30],
        'TURN' => ['Muy Bajo'=>null,'Bajo'=>0.87,'Nominal'=>1.00,'Alto'=>1.07,'Muy Alto'=>1.15],

        'ACAP' => ['Muy Bajo'=>1.46,'Bajo'=>1.19,'Nominal'=>1.00,'Alto'=>0.86,'Muy Alto'=>0.71],
        'AEXP' => ['Muy Bajo'=>1.29,'Bajo'=>1.13,'Nominal'=>1.00,'Alto'=>0.91,'Muy Alto'=>0.82],
        'PCAP' => ['Muy Bajo'=>1.42,'Bajo'=>1.17,'Nominal'=>1.00,'Alto'=>0.86,'Muy Alto'=>0.70],
        'VEXP' => ['Muy Bajo'=>1.21,'Bajo'=>1.10,'Nominal'=>1.00,'Alto'=>0.90],
        'LEXP' => ['Muy Bajo'=>1.14,'Bajo'=>1.07,'Nominal'=>1.00,'Alto'=>0.95],

        'MODP' => ['Muy Bajo'=>1.24,'Bajo'=>1.10,'Nominal'=>1.00,'Alto'=>0.91,'Muy Alto'=>0.82],
        'TOOL' => ['Muy Bajo'=>1.24,'Bajo'=>1.10,'Nominal'=>1.00,'Alto'=>0.91,'Muy Alto'=>0.83],
        'SCED' => ['Muy Bajo'=>1.23,'Bajo'=>1.08,'Nominal'=>1.00,'Alto'=>1.04,'Muy Alto'=>1.10],
    ],

    'ratings' => ['Muy Bajo','Bajo','Nominal','Alto','Muy Alto','Extra Alto'],
];
