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
        $config = array(
            'table_name' => 'companies',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\Company',
        );
        $this->factory->setConfig($config);

        $repository = $this->factory->createService(Bootstrap::getServiceManager());

        $this->assertInstanceOf('GtnPersistZendDb\Infrastructure\ZendDbRepository', $repository);
        $this->assertEquals($config, $repository->getConfig());
        $this->assertEquals('companies', $repository->getTableName());
        $this->assertEquals('id', $repository->getTableId());
        $this->assertEquals('GtnPersistZendDbTest\Model\Company', $repository->getAggregateRootClass());
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\ClassMethods', $repository->getAggregateRootHydrator());
    }

    /** @test */
    public function canCreateSimpleRepositoryWithProxyFactory()
    {
        $config = array(
            'table_name' => 'companies',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\Company',
            'aggregate_root_proxy_factory' => 'GtnPersistZendDbTest\Service\CompanyProxyFactory',
        );
        $this->factory->setConfig($config);

        $repository = $this->factory->createService(Bootstrap::getServiceManager());

        $this->assertInstanceOf('GtnPersistZendDb\Infrastructure\ZendDbRepository', $repository);
        /** @var CompanyProxyFactory $proxyFactory */
        $proxyFactory = $repository->getAggregateRootProxyFactory();
        $this->assertInstanceOf('GtnPersistZendDbTest\Service\CompanyProxyFactory', $proxyFactory);
        $this->assertEquals(Bootstrap::getServiceManager(), $proxyFactory->getServiceManager());
        $this->assertEquals($config, $proxyFactory->getConfig());
    }

    /**
     * @test
     * @expectedException \GtnPersistZendDb\Exception\UnexpectedValueException
     * @expectedExceptionMessage GtnPersistZendDbTest\Service\InvalidProxyFactory: aggregate_root_proxy_factory must implement GtnPersistZendDb\Service\AggregateRootProxyFactoryInterface
     */
    public function cannotCreateRepositoryWithInvalidProxyFactory()
    {
        $this->factory->setConfig(array(
            'table_name' => 'companies',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\Company',
            'aggregate_root_proxy_factory' => 'GtnPersistZendDbTest\Service\InvalidProxyFactory',
        ));

        $this->factory->createService(Bootstrap::getServiceManager());
    }

    /** @test */
    public function canCreateCustomRepository()
    {
        $this->factory->setConfig(array(
            'table_name' => 'users',
            'table_id' => 'user_id',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\User',
            'aggregate_root_hydrator_class' => 'GtnPersistZendDbTest\Infrastructure\UserHydrator',
            'repository_class' => 'GtnPersistZendDbTest\Infrastructure\ZendDbUserRepository',
        ));

        $repository = $this->factory->createService(Bootstrap::getServiceManager());

        $this->assertInstanceOf('GtnPersistZendDbTest\Infrastructure\ZendDbUserRepository', $repository);
        $this->assertEquals('users', $repository->getTableName());
        $this->assertEquals('user_id', $repository->getTableId());
        $this->assertEquals('GtnPersistZendDbTest\Model\User', $repository->getAggregateRootClass());
        $this->assertInstanceOf('GtnPersistZendDbTest\Infrastructure\UserHydrator', $repository->getAggregateRootHydrator());
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
     * @expectedExceptionMessage GtnPersistZendDbTest\Infrastructure\InvalidHydrator: aggregate_root_hydrator_class must implement Zend\Stdlib\Hydrator\HydratorInterface
     */
    public function cannotCreateCustomRepositoryWithInvalidHydratorClass()
    {
        $this->factory->setConfig(array(
            'table_name' => 'users',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\User',
            'aggregate_root_hydrator_class' => 'GtnPersistZendDbTest\Infrastructure\InvalidHydrator',
        ));

        $this->factory->createService(Bootstrap::getServiceManager());
    }

    /**
     * @test
     * @expectedException \GtnPersistZendDb\Exception\UnexpectedValueException
     * @expectedExceptionMessage GtnPersistZendDbTest\Model\Invalid: aggregate_root_class must implement GtnPersistBase\Model\AggregateRootInterface
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
