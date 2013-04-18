<?php
namespace ZfPersistenceZendDb\Db\Adapter;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Adapter\Adapter;

interface MasterSlavesAdapterInterface
{
    /**
     * @return ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapterInterface
     */
    public function addSlaveAdapter(Adapter $adapter);

    /**
     * @return Zend\Db\Adapter\Adapter
     */
    public function getSlaveAdapter();

    /**
     * @return array
     */
    public function getSlaveAdapters();
}
