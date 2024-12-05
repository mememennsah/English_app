<?php
/**
 * The development database settings. These get merged with the global settings.
 */

// return array(
// 	'default' => array(
// 		'connection'  => array(
// 			'dsn'        => 'mysql:host=localhost;dbname=fuel_dev',
// 			'username'   => 'root',
// 			'password'   => 'root',
// 		),
// 	),
// );

return [
    'default' => [
        'type'        => 'pdo',
        'connection'  => [
            'dsn'        => 'mysql:host=localhost;dbname=fuelphp',
            'username'   => 'root', // MAMPのデフォルトユーザー名
            'password'   => 'root', // MAMPのデフォルトパスワード
            'persistent' => false,
        ],
        'table_prefix' => '',
        'charset'      => 'utf8',
        'caching'      => false,
        'profiling'    => true,
    ],
];

