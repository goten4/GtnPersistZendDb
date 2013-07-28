<?php

use ZfPersistenceBaseTest\ServiceManagerFactory;

ini_set('error_reporting', E_ALL);
defined('TEST_BASE_PATH') || define('TEST_BASE_PATH', __DIR__);

$files = array(__DIR__ . '/../vendor/autoload.php', __DIR__ . '/../../vendor/autoload.php');
foreach ($files as $file) {
    if (file_exists($file)) {
        $loader = require $file;
        break;
    }
}

if (! isset($loader)) {
    throw new RuntimeException('vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
}

/* @var $loader \Composer\Autoload\ClassLoader */
$loader->add('ZfPersistenceBaseTest\\', __DIR__ . '/../../ZfPersistenceBase/tests');
$loader->add('ZfPersistenceZendDbTest\\', __DIR__);

if (file_exists(__DIR__ . '/TestConfiguration.php')) {
    $config = require __DIR__ . '/TestConfiguration.php';
} else {
    $config = require __DIR__ . '/TestConfiguration.php.dist';
}

ServiceManagerFactory::setConfig($config);
unset($files, $file, $loader, $config);
