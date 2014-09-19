<?php
namespace GtnPersistZendDb\Infrastructure\ZendDb;

use GtnPersistBase\Model\AggregateRootInterface;
use GtnPersistBase\Model\RepositoryInterface;
use GtnPersistZendDb\Db\Adapter\MasterSlavesAdapterInterface;
use GtnPersistZendDb\Service\AggregateRootProxyFactoryInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression as SqlExpression;
use Zend\Db\Sql\PreparableSqlInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Stdlib\Hydrator\HydratorInterface;

class Repository implements RepositoryInterface
{
    /** @var MasterSlavesAdapterInterface */
    protected $dbAdapter;

    /** @var Sql */
    protected $masterSql;

    /** @var Sql */
    protected $slaveSql;

    /** @var string */
    protected $tableName;

    /** @var mixed */
    protected $tableId;

    /** @var string */
    protected $tableSequenceName;

    /** @var string */
    protected $aggregateRootClass;

    /** @var AggregateRootProxyFactoryInterface */
    protected $aggregateRootProxyFactory;

    /** @var HydratorInterface */
    protected $aggregateRootHydrator;

    /** @var array */
    protected $config;

    /**
     * @param MasterSlavesAdapterInterface $dbAdapter
     */
    public function __construct(MasterSlavesAdapterInterface $dbAdapter)
    {
        $this->setDbAdapter($dbAdapter);
        $this->setMasterSql(new Sql($this->getDbAdapter()));
        $this->setSlaveSql(new Sql($this->getSlaveDbAdapter()));
    }

    /**
     * @return int
     */
    public function size()
    {
        $resultSet = new ResultSet();
        $resultSet->initialize($this->performRead($this->getSelect()->columns(array(
            'size' => new SqlExpression('COUNT(*)')
        ))));
        return $resultSet->current()->size;
    }

    /**
     * @param mixed $id
     * @return AggregateRootInterface
     */
    public function getById($id)
    {
        return $this->getBy(array(
            $this->getTableName() . '.' . $this->getTableId() => $id
        ));
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->hydrateAggregateRootsFromResult($this->performRead($this->getSelect()));
    }

    /**
     * @param AggregateRootInterface $aggregateRoot
     * @return RepositoryInterface
     */
    public function add(AggregateRootInterface $aggregateRoot)
    {
        $data = $this->getAggregateRootHydrator()->extract($aggregateRoot);
        $insert = $this->getMasterSql()->insert($this->getTableName())->values($data);
        $this->performWrite($insert);
        if ($aggregateRoot->getId() === null) {
            $aggregateRoot->setId($this->getDbAdapter()->getDriver()->getLastGeneratedValue($this->getTableSequenceName()));
        }
        return $this;
    }

    /**
     * @param AggregateRootInterface $aggregateRoot
     * @return RepositoryInterface
     */
    public function update(AggregateRootInterface $aggregateRoot)
    {
        $data = $this->getAggregateRootHydrator()->extract($aggregateRoot);
        $update = $this->getMasterSql()->update($this->getTableName())->set($data);
        $update->where(array(
            $this->getTableId() => $aggregateRoot->getId()
        ));
        $this->performWrite($update);
        return $this;
    }

    /**
     * @param AggregateRootInterface $aggregateRoot
     * @return RepositoryInterface
     */
    public function remove(AggregateRootInterface $aggregateRoot)
    {
        $delete = $this->getMasterSql()->delete($this->getTableName());
        $delete->where(array(
            $this->getTableId() => $aggregateRoot->getId()
        ));
        $this->performWrite($delete);
        return $this;
    }

    /**
     * @param array $aggregateRoots
     * @return RepositoryInterface
     */
    public function removeAll(array $aggregateRoots = NULL)
    {
        $delete = $this->getMasterSql()->delete($this->getTableName());
        if ($aggregateRoots) {
            $ids = array_map(function (AggregateRootInterface $aggregateRoot) {
                return $aggregateRoot->getId();
            }, $aggregateRoots);
            $delete->where->in($this->getTableId(), $ids);
        }
        $this->performWrite($delete);
        return $this;
    }

    /**
     * @return MasterSlavesAdapterInterface
     */
    public function getDbAdapter()
    {
        return $this->dbAdapter;
    }

    /**
     * @param MasterSlavesAdapterInterface $dbAdapter
     * @return Repository
     */
    public function setDbAdapter(MasterSlavesAdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        return $this;
    }

    /**
     * @return \Zend\Db\Adapter\Adapter
     */
    public function getSlaveDbAdapter()
    {
        return $this->getDbAdapter()->getSlaveAdapter();
    }

    /**
     * Get table name.
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Set table name.
     *
     * @param string $tableName
     * @return Repository
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * Get table id.
     *
     * @return mixed
     */
    public function getTableId()
    {
        return $this->tableId;
    }

    /**
     * Set table id.
     *
     * @param mixed $tableId
     * @return Repository
     */
    public function setTableId($tableId)
    {
        $this->tableId = $tableId;
        return $this;
    }

    /**
     * Get TableSequenceName.
     *
     * @return string
     */
    public function getTableSequenceName()
    {
        return $this->tableSequenceName;
    }

    /**
     * Set TableSequenceName.
     *
     * @param string $tableSequenceName
     * @return Repository
     */
    public function setTableSequenceName($tableSequenceName)
    {
        $this->tableSequenceName = $tableSequenceName;
        return $this;
    }

    /**
     * Get aggregate root class name.
     *
     * @return string
     */
    public function getAggregateRootClass()
    {
        return $this->aggregateRootClass;
    }

    /**
     * Set aggregate root class name.
     *
     * @param string $aggregateRootClass
     * @return Repository
     */
    public function setAggregateRootClass($aggregateRootClass)
    {
        $this->aggregateRootClass = $aggregateRootClass;
        return $this;
    }

    /**
     * Get AggregateRootProxyFactory.
     *
     * @return AggregateRootProxyFactoryInterface
     */
    public function getAggregateRootProxyFactory()
    {
        return $this->aggregateRootProxyFactory;
    }

    /**
     * Set AggregateRootProxyFactory.
     *
     * @param AggregateRootProxyFactoryInterface $aggregateRootProxyFactory
     * @return Repository
     */
    public function setAggregateRootProxyFactory($aggregateRootProxyFactory)
    {
        $this->aggregateRootProxyFactory = $aggregateRootProxyFactory;
        return $this;
    }

    /**
     * Get aggregate root hydrator.
     *
     * @return \Zend\Stdlib\Hydrator\HydratorInterface
     */
    public function getAggregateRootHydrator()
    {
        return $this->aggregateRootHydrator;
    }

    /**
     * Set aggregate root hydrator.
     *
     * @param \Zend\Stdlib\Hydrator\HydratorInterface $aggregateRootHydrator
     * @return Repository
     */
    public function setAggregateRootHydrator(HydratorInterface $aggregateRootHydrator)
    {
        $this->aggregateRootHydrator = $aggregateRootHydrator;
        return $this;
    }

    /**
     * Get Config.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set Config.
     *
     * @param array $config
     * @return Repository
     */
    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @param $criteria
     * @return array
     */
    protected function getAllBy($criteria)
    {
        $select = $this->getSelect()->where($criteria);
        return $this->hydrateAggregateRootsFromResult($this->performRead($select));
    }

    /**
     * @param $criteria
     * @return AggregateRootInterface
     */
    protected function getBy($criteria)
    {
        $aggregateRoots = $this->getAllBy($criteria);
        return empty($aggregateRoots) ? null : $aggregateRoots[0];
    }

    /**
     * @return Select
     */
    protected function getSelect()
    {
        return $this->getSlaveSql()->select()->from($this->getTableName());
    }

    /**
     * @param PreparableSqlInterface $preparableSqlInterface
     * @return ResultInterface
     */
    protected function performWrite(PreparableSqlInterface $preparableSqlInterface)
    {
        return $this->getMasterSql()->prepareStatementForSqlObject($preparableSqlInterface)->execute();
    }

    /**
     * @param Select $select
     * @return ResultInterface
     */
    protected function performRead(Select $select)
    {
        return $this->getSlaveSql()->prepareStatementForSqlObject($select)->execute();
    }

    /**
     * @param ResultInterface $result
     * @return array
     */
    protected function hydrateAggregateRootsFromResult(ResultInterface $result)
    {
        $className = $this->getAggregateRootClass();
        $resultSet = new HydratingResultSet($this->getAggregateRootHydrator(), new $className());
        $resultSet->initialize($result);
        $aggregateRoots = array();
        foreach ($resultSet as $aggregateRoot) {
            if ($this->aggregateRootProxyFactory !== null) {
                $aggregateRoots[] = $this->aggregateRootProxyFactory->createProxy($aggregateRoot);
            } else {
                $aggregateRoots[] = $aggregateRoot;
            }
        }
        return $aggregateRoots;
    }

    /**
     * @return Sql
     */
    protected function getMasterSql()
    {
        return $this->masterSql;
    }

    /**
     * @param $masterSql
     * @return Repository
     */
    protected function setMasterSql($masterSql)
    {
        $this->masterSql = $masterSql;
        return $this;
    }

    /**
     * @return Sql
     */
    protected function getSlaveSql()
    {
        return $this->slaveSql;
    }

    /**
     * @param $slaveSql
     * @return Repository
     */
    protected function setSlaveSql($slaveSql)
    {
        $this->slaveSql = $slaveSql;
        return $this;
    }
}
