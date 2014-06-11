<?php
namespace GtnPersistZendDbTest\Service;

use GtnPersistZendDb\Service\ZendDbRepositoryFactory;
use GtnPersistZendDbTest\Bootstrap;

class ZendDbRepositoryFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ZendDbRepositoryFactory
     */
    protected $factory;

    protected function setUp()
    {
        $this->factory = new ZendDbRepositoryFactory();
    }

    /** @test */
    public function canCreateSimpleRepository()
    {
        $this->factory->setConfig(array(
            'table_name' => 'companies',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\Company',
        ));

        $repository = $this->factory->createService(Bootstrap::getServiceManager());

        $this->assertInstanceOf('GtnPersistZendDb\Infrastructure\ZendDbRepository', $repository);
        $this->assertEquals('companies', $repository->getTableName());
        $this->assertEquals('id', $repository->getTableId());
        $this->assertEquals('GtnPersistZendDbTest\Model\Company', $repository->getAggregateRootClass());
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\ClassMethods', $repository->getAggregateRootHydrator());
    }

    /** @test */
    public function canCreateCustomRepository()
    {
        $this->factory->setConfig(array(
            'table_name' => 'users',
            'table_id' => 'user_id',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\User',
            'aggregate_root_hydrator_class' => 'GtnPersistZendDbTest\Model\UserHydrator',
            'repository_class' => 'GtnPersistZendDbTest\Infrastructure\ZendDbUserRepository',
        ));

        $repository = $this->factory->createService(Bootstrap::getServiceManager());

        $this->assertInstanceOf('GtnPersistZendDbTest\Infrastructure\ZendDbUserRepository', $repository);
        $this->assertEquals('users', $repository->getTableName());
        $this->assertEquals('user_id', $repository->getTableId());
        $this->assertEquals('GtnPersistZendDbTest\Model\User', $repository->getAggregateRootClass());
        $this->assertInstanceOf('GtnPersistZendDbTest\Model\UserHydrator', $repository->getAggregateRootHydrator());
    }

    /**
     * @test
     * @expectedException \GtnPersistZendDb\Exception\UnexpectedValueException
     * @expectedExceptionMessage GtnPersistZendDbTest\Infrastructure\ZendDbInvalidRepository: repository_class must extend GtnPersistZendDb\Infrastructure\ZendDbRepository
     */
    public function cannotCreateCustomRepositoryWithInvalidRepositoryClass()
    {
        $this->factory->setConfig(array(
            'table_name' => 'users',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\User',
            'repository_class' => 'GtnPersistZendDbTest\Infrastructure\ZendDbInvalidRepository',
        ));

        $this->factory->createService(Bootstrap::getServiceManager());
    }

    /**
     * @test
     * @expectedException \GtnPersistZendDb\Exception\MissingConfigurationException
     * @expectedExceptionMessage table_name is missing in repository configuration
     */
    public function cannotCreateCustomRepositoryWithoutTableName()
    {
        $this->factory->setConfig(array(
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\User',
        ));

        $this->factory->createService(Bootstrap::getServiceManager());
    }

    /**
     * @test
     * @expectedException \GtnPersistZendDb\Exception\MissingConfigurationException
     * @expectedExceptionMessage aggregate_root_class is missing in repository configuration
     */
    public function cannotCreateCustomRepositoryWithoutAggregateRootClass()
    {
        $this->factory->setConfig(array(
            'table_name' => 'users',
        ));

        $this->factory->createService(Bootstrap::getServiceManager());
    }

    /**
     * @test
     * @expectedException \GtnPersistZendDb\Exception\UnexpectedValueException
     * @expectedExceptionMessage GtnPersistZendDbTest\Model\InvalidHydrator: aggregate_root_hydrator_class must implement Zend\Stdlib\Hydrator\HydratorInterface
     */
    public function cannotCreateCustomRepositoryWithInvalidHydratorClass()
    {
        $this->factory->setConfig(array(
            'table_name' => 'users',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\User',
            'aggregate_root_hydrator_class' => 'GtnPersistZendDbTest\Model\InvalidHydrator',
        ));

        $this->factory->createService(Bootstrap::getServiceManager());
    }

    /**
     * @test
     * @expectedException \GtnPersistZendDb\Exception\UnexpectedValueException
     * @expectedExceptionMessage GtnPersistZendDbTest\Model\Invalid: aggregate_root_class must implement GtnPersistBase\Model\AggregateRoot
     */
    public function cannotCreateCustomRepositoryWithInvalidAggregateRootClass()
    {
        $this->factory->setConfig(array(
            'table_name' => 'users',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\Invalid',
        ));

        $this->factory->createService(Bootstrap::getServiceManager());
    }
}
