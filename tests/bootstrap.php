<?php

ini_set('error_reporting', E_ALL);

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

define('ZF_PERSISTENCE_BASE_PATH', dirname(dirname(__DIR__)) . '/ZfPersistenceBase');

/* @var $loader \Composer\Autoload\ClassLoader */
$loader->add('ZfPersistenceBase\\', ZF_PERSISTENCE_BASE_PATH . '/src');
$loader->add('ZfPersistenceBaseTest\\', ZF_PERSISTENCE_BASE_PATH . '/tests');
$loader->add('ZfPersistenceZendDb\\', dirname(__DIR__) . '/src');
$loader->add('ZfPersistenceZendDbTest\\', __DIR__);
unset($files, $file, $loader);

if (!$config = @include __DIR__ . '/testing.config.php') {
    throw new RuntimeException('missing testing configuration file : testing.config.php !');
}
ZfPersistenceZendDbTest\Bootstrap::init($config);