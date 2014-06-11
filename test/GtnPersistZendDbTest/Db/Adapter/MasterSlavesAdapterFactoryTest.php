<?php
namespace GtnPersistZendDbTest\Db\Adapter;

use GtnPersistZendDb\Db\Adapter\MasterSlavesAdapterFactory;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

class MasterSlavesAdapterFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MasterSlavesAdapterFactory
     */
    protected $factory;

    protected function setUp()
    {
        $this->factory = new MasterSlavesAdapterFactory();
    }

    /** @test */
    public function canCreateMasterSlaveAdapterWithoutSlaves()
    {
        $serviceManager = $this->createServiceManager('master.local');

        $adapter = $this->factory->createService($serviceManager);

        $this->assertInstanceOf('GtnPersistZendDb\Db\Adapter\MasterSlavesAdapter', $adapter);
        $this->assertInstanceOf('GtnPersistZendDb\ZendRandomGenerator', $adapter->getRandomGenerator());
    }

    /** @test */
    public function canCreateMasterSlaveAdapterWithoutSlavesAndWithoutMasterKeyInConfiguration()
    {
        $serviceManager = $this->createServiceManagerFromConfig(array(
            'db' => $this->adapterConfig('master.local'),
            'service_manager' => $this->serviceManagerConfig()
        ));

        $adapter = $this->factory->createService($serviceManager);

        $this->assertInstanceOf('GtnPersistZendDb\Db\Adapter\MasterSlavesAdapter', $adapter);
        $this->assertInstanceOf('GtnPersistZendDb\ZendRandomGenerator', $adapter->getRandomGenerator());
    }

    /** @test */
    public function canCreateMasterSlaveAdapterWithSlaves()
    {
        $serviceManager = $this->createServiceManager('master.local', array('slave1.local', 'slave2.local'));

        $adapter = $this->factory->createService($serviceManager);

        $this->assertEquals(2, count($adapter->getSlaveAdapters()));
    }

    /**
     * @param string $master
     * @param array  $slaves
     * @return ServiceManager
     */
    private function createServiceManager($master, array $slaves = array())
    {
        $config = $this->config($master, $slaves);
        return $this->createServiceManagerFromConfig($config);
    }

    /**
     * @param $config
     * @return ServiceManager
     */
    private function createServiceManagerFromConfig($config)
    {
        $serviceManager = new ServiceManager(new Config($config['service_manager']));
        return $serviceManager->setService('Config', $config);
    }

    /**
     * @param string $master
     * @param array  $slaves
     * @return array
     */
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

    /**
     * @return array
     */
    private function serviceManagerConfig()
    {
        $serviceManagerConfig = array(
            'factories' => array(
                'Zend\Db\Adapter\Adapter' => 'GtnPersistZendDb\Db\Adapter\MasterSlavesAdapterFactory'
            ),
            'invokables' => array(
                'GtnPersist\RandomGenerator' => 'GtnPersistZendDb\ZendRandomGenerator'
            )
        );
        return $serviceManagerConfig;
    }

    /**
     * @param string $hostname
     * @return array
     */
    private function adapterConfig($hostname)
    {
        return array(
            'driver' => 'Pdo',
            'dsn' => 'mysql:dbname=test;host=' . $hostname
        );
    }
}
