<?php
namespace ZfPersistenceZendDb\Infrastructure;

use ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapterInterface;
use ZfPersistenceBase\Model\AggregateRoot;
use ZfPersistenceBase\Model\Repository;

class ZendDbRepository implements Repository
{
    protected $dbAdapter;
    
    public function __construct(MasterSlavesAdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }
    
    /**
     * @return Adapter
     */
    public function getDbAdapter()
    {
        return $this->dbAdapter;
    }
    
    public function add(AggregateRoot $aggregateRoot)
    {
    }

    public function getAll()
    {
    }

    public function size()
    {
    }

    public function getById($id)
    {
    }

    public function update(AggregateRoot $aggregateRoot)
    {
    }

    public function remove(AggregateRoot $aggregateRoot)
    {
    }

    public function removeAll(array $aggregateRoots = NULL)
    {
    }
}
