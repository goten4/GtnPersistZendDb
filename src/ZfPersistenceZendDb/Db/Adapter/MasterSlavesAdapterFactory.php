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
        $adapter->setServiceManager($serviceLocator);
        if (array_key_exists('slaves', $config['db'])) {
            foreach ($config['db']['slaves'] as $slaveConfig) {
                $adapter->addSlaveAdapter(new Adapter($slaveConfig));
            }
        }
		return $adapter;
    }
}