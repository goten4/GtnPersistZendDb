<?php
namespace GtnPersistZendDbTest\Service;

use GtnPersistZendDb\Infrastructure\ZendDbRepository;
use GtnPersistZendDb\Service\ZendDbRepositoryAbstractFactory;
use GtnPersistZendDbTest\Bootstrap;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

class ZendDbRepositoryAbstractFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ZendDbRepositoryAbstractFactory
     */
    protected $factory;

    protected function setUp()
    {
        $this->factory = new ZendDbRepositoryAbstractFactory();
    }

    /** @test */
    public function canCreateServiceShouldReturnFalseForUnknownRepository()
    {
        $serviceManager = $this->getServiceManager(array(
            'zenddb_repositories' => array(
            ),
        ));

        $this->assertFalse($serviceManager->has('UnknownRepository'));
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

        $this->assertTrue($serviceManager->has('UserRepository'));
    }

    /** @test */
    public function canCreateServiceShouldReturnFalseIfNoRepositoriesEntriesInConfig()
    {
        $serviceManager = $this->getServiceManager(array());

        $this->assertFalse($serviceManager->has('UnknownRepository'));
    }

    /** @test */
    public function canCreateServiceShouldReturnFalseIfNoConfig()
    {
        $serviceManager = $this->getServiceManager(null);

        $this->assertFalse($serviceManager->has('UnknownRepository'));
    }

    /** @test */
    public function canCreateServiceShouldReturnFalseIfRepositoryEntryIsNotAnArray()
    {
        $serviceManager = $this->getServiceManager(array('zenddb_repositories' => ''));

        $this->assertFalse($serviceManager->has('UnknownRepository'));
    }

    /** @test */
    public function createServiceShouldReturnZendDbRepositoryInstance()
    {
        /** @var ZendDbRepository $repository */
        $repository = $this->factory->createServiceWithName(Bootstrap::getServiceManager(), 'companyrepository', 'CompanyRepository');

        $this->assertInstanceOf('GtnPersistZendDb\Infrastructure\ZendDbRepository', $repository);
    }

    /** @test */
    public function canCreateServiceWithCustomConfig()
    {
        /** @var ZendDbRepository $repository */
        $repository = $this->factory->createServiceWithName(Bootstrap::getServiceManager(), 'userrepository', 'UserRepository');

        $this->assertInstanceOf('GtnPersistZendDbTest\Infrastructure\ZendDbUserRepository', $repository);
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
                'GtnPersistZendDb\Service\ZendDbRepositoryAbstractFactory',
            ),
        )));
        if ($config !== null) {
            $serviceManager->setService('Config', $config);
        }
        return $serviceManager;
    }
}
