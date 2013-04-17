<?php
namespace ZfPersistenceZendDbTest\Db\Adapter;

use ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapterFactory;
use ZfPersistenceZendDbTest\Bootstrap;

class MasterSlavesAdapterFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $factory;

    protected function setUp()
    {
        $this->factory = new MasterSlavesAdapterFactory();
    }

    /** @test */
    public function canCreateMasterSlaveAdapterWithoutSlaves()
    {
        $serviceManager = clone (Bootstrap::serviceManager());
        $serviceManager->setAllowOverride(true);
        $config = $serviceManager->get('Config');
        unset($config['db']['slaves']);
        $serviceManager->setService('Config', $config);
        
        $adapter = $this->factory->createService($serviceManager);
        
        $this->assertInstanceOf('ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapter', $adapter->getSlaveAdapter());
    }

    /** @test */
    public function canCreateMasterSlaveAdapterWithSlaves()
    {
        $adapter = $this->factory->createService(Bootstrap::serviceManager());
        
        $this->assertNotInstanceOf('ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapter', $adapter->getSlaveAdapter());
        $this->assertInstanceOf('Zend\Db\Adapter\Adapter', $adapter);
    }
}
