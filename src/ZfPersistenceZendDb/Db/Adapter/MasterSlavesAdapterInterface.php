<?php
namespace ZfPersistenceZendDb\Db\Adapter;

use Zend\Db\Adapter\Adapter;

interface MasterSlavesAdapterInterface
{

    /**
     * @return Zend\Db\Adapter
     */
    public function addSlaveAdapter(Adapter $adapter);

    /**
     * @return Zend\Db\Adapter
     */
    public function getSlaveAdapter();
}
