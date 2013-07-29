<?php
namespace ZfPersistenceZendDbTest\Infrastructure;

use ZfPersistenceBaseTest\Model\User;
use ZfPersistenceBaseTest\ServiceManagerFactory;

class ZendDbUserRepositoryTest extends \PHPUnit_Extensions_Database_TestCase
{
    protected static $serviceManager;
    protected static $connection;
    protected static $repository;

    public static function setUpBeforeClass()
    {
        static::$serviceManager = ServiceManagerFactory::getServiceManager();
        static::$connection = static::$serviceManager->get('Zend\Db\Adapter\Adapter')->getDriver()->getConnection()->getResource();
        static::$connection->exec(file_get_contents(TEST_BASE_PATH . '/data/schema.sql'));
        static::$repository = static::$serviceManager->get('ZfPersistence\UserRepository');
    }

    public function getConnection()
    {
        return $this->createDefaultDBConnection(static::$connection);
    }

    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(TEST_BASE_PATH . '/data/fixtures.xml');
    }

    /** @test */
    public function canGetSize()
    {
        $this->assertEquals(3, static::$repository->size());
    }

    /** @test */
    public function canAdd()
    {
        static::$repository->add(new User('Bill'));
        
        $this->assertEquals(4, static::$repository->size());
    }

    /** @test */
    public function cannotGetByUnknownId()
    {
        $this->assertNull(static::$repository->getById(99));
    }

    /** @test */
    public function canGetById()
    {
        $storedAggregateRoot = static::$repository->getById(1);
        
        $this->assertInstanceOf('ZfPersistenceBaseTest\Model\User', $storedAggregateRoot);
        $this->assertEquals('John', $storedAggregateRoot->getName());
    }

    /** @test */
    public function canGetAll()
    {
        $aggregateRoots = static::$repository->getAll();
        
        $this->assertInternalType('array', $aggregateRoots);
        $this->assertEquals(3, count($aggregateRoots));
        $this->assertInstanceOf('ZfPersistenceBaseTest\Model\User', $aggregateRoots[0]);
    }

    /** @test */
    public function canUpdate()
    {
        $aggregateRoot = static::$repository->getById(1)->setName('Jack');
        
        static::$repository->update($aggregateRoot);
        
        $this->assertEquals('Jack', static::$repository->getById(1)->getName());
    }

    /** @test */
    public function canRemove()
    {
        $aggregateRoot = static::$repository->getById(1);
        
        static::$repository->remove($aggregateRoot);
        
        $this->assertEquals(2, static::$repository->size());
        $this->assertNull(static::$repository->getById(1));
    }

    /** @test */
    public function canRemoveAllSpecifiedAggregateRoots()
    {
        $aggregateRoot1 = static::$repository->getById(1);
        $aggregateRoot2 = static::$repository->getById(2);
        static::$repository->removeAll(array(
            $aggregateRoot1, 
            $aggregateRoot2
        ));
        
        $this->assertEquals(1, static::$repository->size());
        $this->assertNull(static::$repository->getById(1));
        $this->assertNull(static::$repository->getById(2));
    }

    /** @test */
    public function canRemoveAllAggregateRoots()
    {
        static::$repository->removeAll();
        
        $this->assertEquals(0, static::$repository->size());
    }
}