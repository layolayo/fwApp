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
        'production' => [
            'adapter' => 'mysql',
            'host' => 'sql649.your-server.de',
            'name' => 'facilik_db1',
            'user' => 'facilik_1',
            'pass' => 'ZBY6E6Ei1Ctw5D34',
            'port' => '3306',
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation'
];
