<?php
namespace GtnPersistZendDbTest\Service;

use GtnPersistZendDb\Db\Adapter\MasterSlavesAdapter;
use GtnPersistZendDb\Service\ZendDbRepositoryFactory;
use GtnPersistZendDbTest\Infrastructure\ZendDbUserRepository;
use Zend\ServiceManager\ServiceLocatorInterface;

class ZendDbUserRepositoryFactory extends ZendDbRepositoryFactory
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var MasterSlavesAdapter $dbAdapter */
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');

        return new ZendDbUserRepository($dbAdapter);
    }
}
