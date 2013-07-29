<?php
namespace ZfPersistenceZendDb\Infrastructure;

use Zend\Db\Adapter\Platform\Sqlite;

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
        $this->setSlaveSql(new Sql($this->getDbAdapter()->getSlaveAdapter()));
    }

    public function add(AggregateRoot $aggregateRoot)
    {
        $insert = $this->getMasterSql()->insert($this->tableName())->values($aggregateRoot->getArrayCopy());
        $this->getMasterSql()->prepareStatementForSqlObject($insert)->execute();
    }

    public function getAll()
    {
        return $this->executeSelect($this->getSelect());
    }

    public function size()
    {
        $resultSet = new ResultSet();
        $select = $this->getSelect()->columns(array(
            'size' => new SqlExpression('COUNT(*)')
        ));
        $resultSet->initialize($this->getSlaveSql()->prepareStatementForSqlObject($select)->execute());
        return $resultSet->current()->size;
    }

    public function getById($id)
    {
        return $this->getBy(array(
            $this->id() => $id
        ));
    }

    public function update(AggregateRoot $aggregateRoot)
    {
        $update = $this->getMasterSql()->update($this->tableName())->set($aggregateRoot->getArrayCopy())->where(
            array(
                $this->id() => $aggregateRoot->getId()
            ));
        $this->getMasterSql()->prepareStatementForSqlObject($update)->execute();
    }

    public function remove(AggregateRoot $aggregateRoot)
    {
        $delete = $this->getMasterSql()->delete($this->tableName())->where(
            array(
                $this->id() => $aggregateRoot->getId()
            ));
        $this->getMasterSql()->prepareStatementForSqlObject($delete)->execute();
    }

    public function removeAll(array $aggregateRoots = NULL)
    {
        $delete = $this->getMasterSql()->delete($this->tableName());
        if ($aggregateRoots) {
            $ids = array_map(function (AggregateRoot $aggregateRoot) { return $aggregateRoot->getId(); }, $aggregateRoots);
            $delete->where->in($this->id(), $ids);
        }
        $this->getMasterSql()->prepareStatementForSqlObject($delete)->execute();
    }

    protected function getAllBy($criteria)
    {
        return $this->executeSelect($this->getSelect()->where($criteria));
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

    protected function executeSelect($select)
    {
        $statement = $this->getSlaveSql()->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $className = $this->aggregateRootClassName();
        $resultSet = new HydratingResultSet($this->getHydrator(), new $className());
        $resultSet->initialize($result);
        $aggregateRoots = array();
        foreach ($resultSet as $aggregateRoot) {
            $aggregateRoots[] = $aggregateRoot;
        }
        return $aggregateRoots;
    }

    protected function id()
    {
        return 'id';
    }

    protected function getHydrator()
    {
        return new ArraySerializable();
    }

    public function getDbAdapter()
    {
        return $this->dbAdapter;
    }

    public function getSlaveDbAdapter()
    {
        return $this->getDbAdapter()->getSlaveAdapter();
    }

    public function setDbAdapter(MasterSlavesAdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        return $this;
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

    protected abstract function tableName();

    protected abstract function aggregateRootClassName();
}
