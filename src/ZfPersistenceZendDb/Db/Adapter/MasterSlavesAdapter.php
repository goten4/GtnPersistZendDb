<?php
namespace ZfPersistenceZendDb\Db\Adapter;

use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Db\Adapter\Adapter;

class MasterSlavesAdapter extends Adapter implements MasterSlavesAdapterInterface
{
    protected $slaveAdapters = array();
    protected $serviceManager;

    /**
     * @return Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }
    
	/**
     * @return ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapter
     */
    public function setServiceManager(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceManager = $serviceLocator;
        return $this;
    }

	/**
     * @return ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapter
     */
    public function addSlaveAdapter(Adapter $adapter)
    {
        $this->slaveAdapters[] = $adapter;
        return $this;
    }

    /**
     * @return Zend\Db\Adapter\Adapter
     */
    public function getSlaveAdapter()
    {
        $slaveAdaptersCount = count($this->slaveAdapters);
        if ($slaveAdaptersCount == 0) {
            return $this;
        }
        return $this->slaveAdapters[$this->getServiceManager()->get('ZfPersistence\RandomGenerator')->getInteger(0, $slaveAdaptersCount-1)];
    }

    /**
     * @return array
     */
    public function getSlaveAdapters()
    {
        return $this->slaveAdapters;
    }
}
