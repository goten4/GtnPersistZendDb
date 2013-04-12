<?php
namespace ZfPersistenceZendDb\Db\Adapter;

class MasterSlavesAdapter implements MasterSlavesAdapterInterface
{
    protected $slaveAdapters = array();

    public function addSlaveAdapter(Adapter $adapter)
    {
        $this->slaveAdapters[] = $adapter;
        return $this;
    }

    public function getSlaveAdapter()
    {
    }
}
