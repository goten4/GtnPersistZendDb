<?php
namespace ZfPersistenceZendDb\Infrastructure;

use Zend\ServiceManager\FactoryInterface;

class ZendDbRepositoryFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ZendDbRepository($serviceLocator->get('zfpersistence.db.adapter'));
    }
}