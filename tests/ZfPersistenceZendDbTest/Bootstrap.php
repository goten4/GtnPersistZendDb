<?php
namespace ZfPersistenceZendDbTest;

use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

class Bootstrap
{
    private static $serviceManager;
    
    public static function init(array $config)
    {
        $serviceManager = new ServiceManager(new ServiceManagerConfig($config['service_manager']));
        $serviceManager->setService('Config', $config);
        static::$serviceManager = $serviceManager;
    }
    
    public static function serviceManager()
    {
        if (static::$serviceManager == NULL) {
            throw new \RuntimeException('Bootstrap has not been initialized !');
        }
        return static::$serviceManager;
    }
}