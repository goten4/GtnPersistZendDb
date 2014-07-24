<?php
namespace GtnPersistZendDbTest\Infrastructure\ZendDb;

use GtnPersistZendDb\Db\Adapter\MasterSlavesAdapter;
use GtnPersistZendDb\Infrastructure\ZendDb\RepositoryFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserRepositoryFactory extends RepositoryFactory
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var MasterSlavesAdapter $dbAdapter */
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');

        return new UserRepository($dbAdapter);
    }
}
