<?php
namespace GtnPersistZendDb\Db\Adapter;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;

interface MasterSlavesAdapterInterface extends AdapterInterface
{
    /**
     * @param Adapter $adapter
     * @return MasterSlavesAdapterInterface
     */
    public function addSlaveAdapter(Adapter $adapter);

    /**
     * @return Adapter
     */
    public function getSlaveAdapter();

    /**
     * @return array
     */
    public function getSlaveAdapters();
}
