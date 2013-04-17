<?php
namespace ZfPersistenceZendDb;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;

class Module implements AutoloaderProviderInterface, ServiceProviderInterface
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                MODULE_BASE_DIR . '/autoload_classmap.php',
            ),
            AutoloaderFactory::STANDARD_AUTOLOADER => array(
                StandardAutoloader::LOAD_NS => array(
                    __NAMESPACE__ => __DIR__
                )
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Zend\Db\Adapter' => 'ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapterFactory',
                'ZfPersistence\Repository' => 'ZfPersistenceZendDb\Infrastructure\ZendDbRepositoryFactory'
            )
        );
    }
}