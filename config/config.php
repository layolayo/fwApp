<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

\Sentry\init(['dsn' => 'https://d4412d9e9b1448a6ad76f2f7f4a27490@o1155143.ingest.sentry.io/6245341' ]);
