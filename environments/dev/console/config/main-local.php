<?php
return [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.01;dbname=globelink',
            'username' => 'globelink',
            'password' => 'globelink',
            'charset' => 'utf8',
        ],
    ],
];
