<?php

$testingConfigCachePath = dirname(__DIR__).'/bootstrap/cache/testing-config.php';

putenv('APP_CONFIG_CACHE='.$testingConfigCachePath);
$_ENV['APP_CONFIG_CACHE'] = $testingConfigCachePath;
$_SERVER['APP_CONFIG_CACHE'] = $testingConfigCachePath;

$forcedTestingEnvironment = [
    'APP_ENV' => 'testing',
    'CACHE_STORE' => 'array',
    'DB_CONNECTION' => 'sqlite',
    'DB_DATABASE' => ':memory:',
    'DB_URL' => '',
];

foreach ($forcedTestingEnvironment as $key => $value) {
    putenv($key.'='.$value);
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

require dirname(__DIR__).'/vendor/autoload.php';
