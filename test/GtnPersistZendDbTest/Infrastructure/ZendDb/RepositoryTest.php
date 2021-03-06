<?php
namespace GtnPersistZendDbTest\Infrastructure\ZendDb;

use GtnPersistBase\Model\RepositoryInterface;
use GtnPersistZendDbTest\Bootstrap;
use GtnPersistZendDbTest\Model\Company;
use Zend\Db\Adapter\AdapterInterface;

class RepositoryTest extends \PHPUnit_Extensions_Database_TestCase
{
    /**
     * @var \PDO
     */
    protected static $connection;

    /**
     * @var RepositoryInterface
     */
    protected static $repository;

    public static function setUpBeforeClass()
    {
        $serviceManager = Bootstrap::getServiceManager();
        static::$repository = $serviceManager->get('CompanyRepository');

        /** @var $dbAdapter AdapterInterface */
        $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
        static::$connection = $dbAdapter->getDriver()->getConnection()->getResource();

        static::$connection->exec(file_get_contents(TEST_BASE_PATH . '/data/schema.sql'));
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
        $aggregateRoot = new Company('CVF');
        static::$repository->add($aggregateRoot);

        $this->assertEquals(4, $this->getTableCount());
        $this->assertEquals(4, $aggregateRoot->getId());
    }

    /** @test */
    public function cannotGetByUnknownId()
    {
        $this->assertNull(static::$repository->getById(99));
    }

    /** @test */
    public function canGetById()
    {
        /** @var Company $storedAggregateRoot */
        $storedAggregateRoot = static::$repository->getById(1);

        $this->assertInstanceOf('GtnPersistZendDbTest\Model\CompanyProxy', $storedAggregateRoot);
        $this->assertEquals('Apple', $storedAggregateRoot->getName());
    }

    /** @test */
    public function canGetAll()
    {
        $aggregateRoots = static::$repository->getAll();

        $this->assertInternalType('array', $aggregateRoots);
        $this->assertEquals(3, count($aggregateRoots));
        $this->assertInstanceOf('GtnPersistZendDbTest\Model\CompanyProxy', $aggregateRoots[0]);
    }

    /** @test */
    public function canUpdate()
    {
        $aggregateRoot = static::$repository->getById(1)->setName('World Company');

        static::$repository->update($aggregateRoot);

        $this->assertEquals('World Company', static::$connection->query('SELECT name FROM companies WHERE id = 1')->fetchColumn(0));
    }

    /** @test */
    public function canRemove()
    {
        $aggregateRoot = static::$repository->getById(1);

        static::$repository->remove($aggregateRoot);

        $this->assertEquals(2, $this->getTableCount());
        $this->assertFalse($this->isRowPresentInTable(1));
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

        $this->assertEquals(1, $this->getTableCount());
        $this->assertFalse($this->isRowPresentInTable(1));
        $this->assertFalse($this->isRowPresentInTable(2));
    }

    /** @test */
    public function canRemoveAllAggregateRoots()
    {
        static::$repository->removeAll();

        $this->assertEquals(0, $this->getTableCount());
    }

    /**
     * @param int $id
     * @return int
     */
    protected function getTableCount($id = null)
    {
        $statement = 'SELECT count(*) FROM companies';
        if ($id !== null) {
            $statement .= " WHERE id = $id";
        }
        return intval(static::$connection->query($statement)->fetchColumn(0));
    }

    /**
     * @param $id
     * @return int
     */
    protected function isRowPresentInTable($id)
    {
        return $this->getTableCount($id) > 0;
    }
}
