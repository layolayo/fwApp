<?php

return
[
    'paths' => [
        'migrations' => 'migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
//        'production' => [
//            'adapter' => 'mysql',
//            'host' => 'localhost',
//            'name' => 'production_db',
//            'user' => 'root',
//            'pass' => '',
//            'port' => '3306',
//            'charset' => 'utf8',
//        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => 'cub3d.pw',
            'name' => 'fwapp-dev',
            'user' => 'fwapp-user',
            'pass' => 'fwappuserpassword',
            'port' => '3306',
            'charset' => 'utf8',
        ],
//        'testing' => [
//            'adapter' => 'mysql',
//            'host' => 'localhost',
//            'name' => 'testing_db',
//            'user' => 'root',
//            'pass' => '',
//            'port' => '3306',
//            'charset' => 'utf8',
//        ]
    ],
    'version_order' => 'creation'
];
