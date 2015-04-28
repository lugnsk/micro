<?php
return [
    'servers' => [
        'server1'=>[
            'class' => 'stream',
            'ip' => '192.168.10.1',
            'user' => 'name',
            'pass' => 'word'
        ],
        'server2'=>[
            'class' => 'redis',
            'ip' => '192.168.10.2',
            'user' => 'name',
            'pass' => 'word'
        ],
        'server3'=>[
            'class' => 'redis',
            'ip' => '192.168.10.3',
            'user' => 'name',
            'pass' => 'word'
        ],
        'server4'=>[
            'class' => 'rabbit-mq',
            'ip' => '192.168.10.4',
            'user' => 'name',
            'pass' => 'word'
        ]
    ],
    'routes' => [
        'pipeline.service' => 'server1',
        'master.*' => ['server2','server3'],
        'broadcast.*'=> ['server4']
    ]
];