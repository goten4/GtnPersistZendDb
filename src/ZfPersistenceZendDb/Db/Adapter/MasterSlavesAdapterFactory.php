<?php
namespace ZfPersistenceZendDb\Db\Adapter;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\Db\Adapter\Adapter;

class MasterSlavesAdapterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $adapter = new MasterSlavesAdapter($config['db']['master']);
        foreach ($config['db']['slaves'] as $slaveConfig) {
            $adapter->addSlaveAdapter(new Adapter($slaveConfig));
        }
		return $adapter;
    }
}