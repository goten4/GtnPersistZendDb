<?php
namespace ZfPersistenceZendDb\Infrastructure;

use ZfPersistenceBase\Model\Entity;
use ZfPersistenceBase\Model\Repository;

class ZendDbRepository implements Repository
{
    public function __construct()
    {
        
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
