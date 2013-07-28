<?php
namespace ZfPersistenceZendDbTest\Infrastructure;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class ZendDbUserRepositoryFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ZendDbUserRepository($serviceLocator->get('Zend\Db\Adapter\Adapter'));
    }
}