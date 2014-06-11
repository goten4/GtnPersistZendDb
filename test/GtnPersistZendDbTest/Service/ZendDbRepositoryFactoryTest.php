<?php
namespace GtnPersistZendDbTest\Service;

use GtnPersistZendDb\Service\ZendDbRepositoryFactory;
use GtnPersistZendDbTest\Bootstrap;

class ZendDbRepositoryFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canCreateSimpleRepository()
    {
        $factory = new ZendDbRepositoryFactory();
        $factory->setConfig(array(
            'table_name' => 'companies',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\Company',
        ));

        $repository = $factory->createService(Bootstrap::getServiceManager());

        $this->assertInstanceOf('GtnPersistZendDb\Infrastructure\ZendDbRepository', $repository);
        $this->assertEquals('companies', $repository->getTableName());
        $this->assertEquals('id', $repository->getTableId());
        $this->assertEquals('GtnPersistZendDbTest\Model\Company', $repository->getAggregateRootClass());
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\ClassMethods', $repository->getAggregateRootHydrator());
    }

    /** @test */
    public function canCreateCustomRepository()
    {
        $factory = new ZendDbRepositoryFactory();
        $factory->setConfig(array(
            'table_name' => 'users',
            'table_id' => 'user_id',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\User',
            'aggregate_root_hydrator_class' => 'GtnPersistZendDbTest\Model\UserHydrator',
            'repository_class' => 'GtnPersistZendDbTest\Infrastructure\ZendDbUserRepository',
        ));

        $repository = $factory->createService(Bootstrap::getServiceManager());

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
        $factory = new ZendDbRepositoryFactory();
        $factory->setConfig(array(
            'table_name' => 'users',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\User',
            'repository_class' => 'GtnPersistZendDbTest\Infrastructure\ZendDbInvalidRepository',
        ));

        $factory->createService(Bootstrap::getServiceManager());
    }

    /**
     * @test
     * @expectedException \GtnPersistZendDb\Exception\MissingConfigurationException
     * @expectedExceptionMessage table_name is missing in repository configuration
     */
    public function cannotCreateCustomRepositoryWithoutTableName()
    {
        $factory = new ZendDbRepositoryFactory();
        $factory->setConfig(array(
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\User',
        ));

        $factory->createService(Bootstrap::getServiceManager());
    }

    /**
     * @test
     * @expectedException \GtnPersistZendDb\Exception\MissingConfigurationException
     * @expectedExceptionMessage aggregate_root_class is missing in repository configuration
     */
    public function cannotCreateCustomRepositoryWithoutAggregateRootClass()
    {
        $factory = new ZendDbRepositoryFactory();
        $factory->setConfig(array(
            'table_name' => 'users',
        ));

        $factory->createService(Bootstrap::getServiceManager());
    }

    /**
     * @test
     * @expectedException \GtnPersistZendDb\Exception\UnexpectedValueException
     * @expectedExceptionMessage GtnPersistZendDbTest\Model\InvalidHydrator: aggregate_root_hydrator_class must implement Zend\Stdlib\Hydrator\HydratorInterface
     */
    public function cannotCreateCustomRepositoryWithInvalidHydratorClass()
    {
        $factory = new ZendDbRepositoryFactory();
        $factory->setConfig(array(
            'table_name' => 'users',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\User',
            'aggregate_root_hydrator_class' => 'GtnPersistZendDbTest\Model\InvalidHydrator',
        ));

        $factory->createService(Bootstrap::getServiceManager());
    }

    /**
     * @test
     * @expectedException \GtnPersistZendDb\Exception\UnexpectedValueException
     * @expectedExceptionMessage GtnPersistZendDbTest\Model\Invalid: aggregate_root_class must implement GtnPersistBase\Model\AggregateRoot
     */
    public function cannotCreateCustomRepositoryWithInvalidAggregateRootClass()
    {
        $factory = new ZendDbRepositoryFactory();
        $factory->setConfig(array(
            'table_name' => 'users',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\Invalid',
        ));

        $factory->createService(Bootstrap::getServiceManager());
    }
}
