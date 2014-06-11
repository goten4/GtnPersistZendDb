<?php
namespace GtnPersistZendDb\Db\Adapter;

use GtnPersistZendDb\RandomGeneratorInterface;
use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MasterSlavesAdapterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $masterConfig = array_key_exists('master', $config['db']) ? $config['db']['master'] : $config['db'];
        $adapter = new MasterSlavesAdapter($masterConfig);

        /** @var RandomGeneratorInterface $randomGenerator */
        $randomGenerator = $serviceLocator->get('GtnPersist\RandomGenerator');
        $adapter->setRandomGenerator($randomGenerator);

        if (array_key_exists('slaves', $config['db'])) {
            foreach ($config['db']['slaves'] as $slaveConfig) {
                $adapter->addSlaveAdapter(new Adapter($slaveConfig));
            }
        }

        return $adapter;
    }
}
