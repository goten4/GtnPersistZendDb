<?php
namespace GtnPersistZendDbTest\Infrastructure\ZendDb;

use GtnPersistZendDb\Infrastructure\ZendDb\RepositoryAbstractFactory;
use GtnPersistZendDbTest\Bootstrap;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

class RepositoryAbstractFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \GtnPersistZendDb\Infrastructure\ZendDb\RepositoryAbstractFactory
     */
    protected $factory;

    protected function setUp()
    {
        $this->factory = new RepositoryAbstractFactory();
    }

    /** @test */
    public function canCreateServiceShouldReturnFalseForUnknownRepository()
    {
        $serviceManager = $this->getServiceManager(array(
            'zenddb_repositories' => array(
            ),
        ));

        $this->assertFalse($this->factory->canCreateServiceWithName($serviceManager, 'unknownrepository', 'UnknownRepository'));
    }

    /** @test */
    public function canCreateServiceShouldReturnTrueForValidRepository()
    {
        $serviceManager = $this->getServiceManager(array(
            'zenddb_repositories' => array(
                'UserRepository' => array(
                    'table_name' => 'users',
                    'table_id' => 'user_id',
                ),
            ),
        ));

        $this->assertTrue($this->factory->canCreateServiceWithName($serviceManager, 'userrepository', 'UserRepository'));
    }

    /** @test */
    public function canCreateServiceShouldReturnFalseIfNoRepositoriesEntriesInConfig()
    {
        $serviceManager = $this->getServiceManager(array());

        $this->assertFalse($this->factory->canCreateServiceWithName($serviceManager, 'unknownrepository', 'UnknownRepository'));
    }

    /** @test */
    public function canCreateServiceShouldReturnFalseIfNoConfig()
    {
        $serviceManager = $this->getServiceManager(null);

        $this->assertFalse($this->factory->canCreateServiceWithName($serviceManager, 'unknownrepository', 'UnknownRepository'));
    }

    /** @test */
    public function canCreateServiceShouldReturnFalseIfRepositoryEntryIsNotAnArray()
    {
        $serviceManager = $this->getServiceManager(array('zenddb_repositories' => ''));

        $this->assertFalse($this->factory->canCreateServiceWithName($serviceManager, 'unknownrepository', 'UnknownRepository'));
    }

    /** @test */
    public function createServiceShouldReturnZendDbRepositoryInstance()
    {
        /** @var \GtnPersistZendDb\Infrastructure\ZendDb\Repository $repository */
        $repository = $this->factory->createServiceWithName(Bootstrap::getServiceManager(), 'companyrepository', 'CompanyRepository');

        $this->assertInstanceOf('GtnPersistZendDb\Infrastructure\ZendDb\Repository', $repository);
    }

    /** @test */
    public function canCreateServiceWithCustomConfig()
    {
        /** @var \GtnPersistZendDb\Infrastructure\ZendDb\Repository $repository */
        $repository = $this->factory->createServiceWithName(Bootstrap::getServiceManager(), 'userrepository', 'UserRepository');

        $this->assertInstanceOf('GtnPersistZendDbTest\Infrastructure\ZendDb\UserRepository', $repository);
    }

    /**
     * @param array $config
     * @return ServiceManager
     */
    protected function getServiceManager(array $config = null)
    {
        $serviceManager = new ServiceManager(new ServiceManagerConfig(array(
            'factories' => array(
                'Zend\Db\Adapter\Adapter' => 'GtnPersistZendDb\Db\Adapter\MasterSlavesAdapterFactory',
            ),
            'abstract_factories' => array(
                'GtnPersistZendDb\Infrastructure\ZendDb\RepositoryAbstractFactory',
            ),
        )));
        if ($config !== null) {
            $serviceManager->setService('Config', $config);
        }
        return $serviceManager;
    }
}
