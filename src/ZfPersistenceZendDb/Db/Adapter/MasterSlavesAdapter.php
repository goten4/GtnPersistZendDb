<?php
namespace ZfPersistenceZendDb\Db\Adapter;

use Zend\Db\Adapter\Adapter;

class MasterSlavesAdapter extends Adapter implements MasterSlavesAdapterInterface
{
    protected $slaveAdapters = array();

    public function addSlaveAdapter(Adapter $adapter)
    {
        $this->slaveAdapters[] = $adapter;
        return $this;
    }

    public function getSlaveAdapter()
    {
        $slaveAdaptersCount = count($this->slaveAdapters);
        if ($slaveAdaptersCount == 0) {
            return $this;
        }
        return $this->slaveAdapters[rand(0, $slaveAdaptersCount-1)];
    }
}
