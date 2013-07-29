<?php
namespace ZfPersistenceZendDb\Infrastructure;

use Zend\Db\Sql\PreparableSqlInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression as SqlExpression;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\ArraySerializable;
use ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapterInterface;
use ZfPersistenceBase\Model\AggregateRoot;
use ZfPersistenceBase\Model\Repository;

abstract class ZendDbRepository implements Repository
{
    protected $dbAdapter;
    protected $masterSql;
    protected $slaveSql;
    
    public function __construct(MasterSlavesAdapterInterface $dbAdapter)
    {
        $this->setDbAdapter($dbAdapter);
        $this->setMasterSql(new Sql($this->getDbAdapter()));
        $this->setSlaveSql(new Sql($this->getSlaveDbAdapter()));
    }
    
    /**
     * Provides the name of the table associated to the Repository.
     * @return string
     */
    protected abstract function tableName();
    
    /**
     * Provides the class name of the aggregate root associated to the Repository.
     * @return string
     */
    protected abstract function aggregateRootClassName();
    
    /**
     * Provides the table field name of the aggregate root's identifier.
     * 'id' by default, override this method to change it.
     * @return string
     */
    protected function id()
    {
        return 'id';
    }
    
    /**
     * Provides the hydrator that will be used to convert SQL rows to Aggregate Roots and vice versa.
     * Zend\Stdlib\Hydrator\ArraySerializable by default, override this method to change it.
     * @return Zend\Stdlib\Hydrator\HydratorInterface
     */
    protected function hydrator()
    {
        return new ArraySerializable();
    }

    public function size()
    {
        $resultSet = new ResultSet();
        $resultSet->initialize($this->performRead($this->getSelect()->columns(array(
            'size' => new SqlExpression('COUNT(*)')
        ))));
        return $resultSet->current()->size;
    }
    
    public function getById($id)
    {
        return $this->getBy(array(
            $this->id() => $id
        ));
    }

    public function getAll()
    {
        return $this->hydrateAggregateRootsFromResult($this->performRead($this->getSelect()));
    }
    
    public function add(AggregateRoot $aggregateRoot)
    {
        $data = $this->hydrator()->extract($aggregateRoot);
        $insert = $this->getMasterSql()->insert($this->tableName())->values($data);
        $this->performWrite($insert);
    }

    public function update(AggregateRoot $aggregateRoot)
    {
        $update = $this->getMasterSql()->update($this->tableName())->set($aggregateRoot->getArrayCopy());
        $update->where(array(
            $this->id() => $aggregateRoot->getId()
        ));
        $this->performWrite($update);
    }

    public function remove(AggregateRoot $aggregateRoot)
    {
        $delete = $this->getMasterSql()->delete($this->tableName());
        $delete->where(array(
            $this->id() => $aggregateRoot->getId()
        ));
        $this->performWrite($delete);
    }

    public function removeAll(array $aggregateRoots = NULL)
    {
        $delete = $this->getMasterSql()->delete($this->tableName());
        if ($aggregateRoots) {
            $ids = array_map(function (AggregateRoot $aggregateRoot)
            {
                return $aggregateRoot->getId();
            }, $aggregateRoots);
            $delete->where->in($this->id(), $ids);
        }
        $this->performWrite($delete);
    }

    protected function getAllBy($criteria)
    {
        $select = $this->getSelect()->where($criteria);
		return $this->hydrateAggregateRootsFromResult($this->performRead($select));
    }

    protected function getBy($criteria)
    {
        $aggregateRoots = $this->getAllBy($criteria);
        return empty($aggregateRoots) ? null : $aggregateRoots[0];
    }

    protected function getSelect()
    {
        return $this->getSlaveSql()->select()->from($this->tableName());
    }

    protected function performWrite(PreparableSqlInterface $preparableSqlInterface)
    {
        return $this->getMasterSql()->prepareStatementForSqlObject($preparableSqlInterface)->execute();
    }

    protected function performRead(Select $select)
    {
        return $this->getSlaveSql()->prepareStatementForSqlObject($select)->execute();
    }

    protected function hydrateAggregateRootsFromResult(ResultInterface $result)
    {
        $className = $this->aggregateRootClassName();
        $resultSet = new HydratingResultSet($this->hydrator(), new $className());
        $resultSet->initialize($result);
        $aggregateRoots = array();
        foreach ($resultSet as $aggregateRoot) {
            $aggregateRoots[] = $aggregateRoot;
        }
        return $aggregateRoots;
    }

    public function getDbAdapter()
    {
        return $this->dbAdapter;
    }

    public function setDbAdapter(MasterSlavesAdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        return $this;
    }

    public function getSlaveDbAdapter()
    {
        return $this->getDbAdapter()->getSlaveAdapter();
    }

    protected function getMasterSql()
    {
        return $this->masterSql;
    }

    protected function setMasterSql($masterSql)
    {
        $this->masterSql = $masterSql;
        return $this;
    }

    protected function getSlaveSql()
    {
        return $this->slaveSql;
    }

    protected function setSlaveSql($slaveSql)
    {
        $this->slaveSql = $slaveSql;
        return $this;
    }
}
