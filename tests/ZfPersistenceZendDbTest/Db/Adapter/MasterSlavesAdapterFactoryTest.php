<?php
namespace ZfPersistenceZendDbTest\Db\Adapter;

use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;
use ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapterFactory;

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
        $serviceManager = $this->createServiceManager('master.local');
        
        $adapter = $this->factory->createService($serviceManager);
        
        $this->assertInstanceOf('ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapter', $adapter);
        $this->assertSame($serviceManager, $adapter->getServiceManager());
    }

    /** @test */
    public function canCreateMasterSlaveAdapterWithoutSlavesAndWithoutMasterKeyInConfiguration()
    {
        $serviceManager = $this->createServiceManagerFromConfig(array(
            'db' => $this->adapterConfig('master.local'), 
            'service_manager' => $this->serviceManagerConfig()
        ));
        
        $adapter = $this->factory->createService($serviceManager);
        
        $this->assertInstanceOf('ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapter', $adapter);
        $this->assertSame($serviceManager, $adapter->getServiceManager());
    }

    /** @test */
    public function canCreateMasterSlaveAdapterWithSlaves()
    {
        $serviceManager = $this->createServiceManager('master.local', array('slave1.local', 'slave2.local'));
        
        $adapter = $this->factory->createService($serviceManager);
        
        $this->assertEquals(2, count($adapter->getSlaveAdapters()));
    }

    private function createServiceManager($master, array $slaves = array())
    {
        $config = $this->config($master, $slaves);
        return $this->createServiceManagerFromConfig($config);
    }

    private function createServiceManagerFromConfig($config)
    {
        $serviceManager = new ServiceManager(new Config($config));
        return $serviceManager->setService('Config', $config);
    }

    private function config($master, array $slaves = array())
    {
        $config = array(
            'db' => array(
                'master' => $this->adapterConfig($master)
            ), 
            'service_manager' => $this->serviceManagerConfig()
        );
        foreach ($slaves as $slave) {
            $config['db']['slaves'][] = $this->adapterConfig($slave);
        }
        return $config;
    }

    private function serviceManagerConfig()
    {
        $serviceManagerConfig = array(
            'factories' => array(
                'Zend\Db\Adapter\Adapter' => 'ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapterFactory'
            ), 
            'invokables' => array(
                'ZfPersistence\RandomGenerator' => 'ZfPersistenceZendDb\ZendRandomGenerator'
            )
        );
        return $serviceManagerConfig;
    }

    private function adapterConfig($hostname)
    {
        return array(
            'driver' => 'Pdo', 
            'dsn' => 'mysql:dbname=test;host=' . $hostname
        );
    }
}
