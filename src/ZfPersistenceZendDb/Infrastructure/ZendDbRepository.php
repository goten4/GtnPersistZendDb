<?php
namespace ZfPersistenceZendDb\Infrastructure;

use ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapterInterface;
use ZfPersistenceBase\Model\Entity;
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
    
    public function add(Entity $entity)
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

    public function update(Entity $entity)
    {
    }

    public function remove(Entity $entity)
    {
    }

    public function removeAll(array $entities = NULL)
    {
    }
}
