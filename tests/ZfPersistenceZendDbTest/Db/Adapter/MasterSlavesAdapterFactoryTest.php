<?php
namespace ZfPersistenceZendDbTest\Db\Adapter;

use ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapterFactory;

use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

class MasterSlavesAdapterFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canCreateService()
    {
        $config = array(
            'db' => array(
                'master' => array(
                    'driver' => 'Pdo',
                    'dsn'    => 'mysql:dbname=test;host=master.local'
                ),
                'slaves' => array(
                    array(
                        'driver' => 'Pdo',
                        'dsn'    => 'mysql:dbname=test;host=slave1.local'
                    ),
                    array(
                        'driver' => 'Pdo',
                        'dsn'    => 'mysql:dbname=test;host=slave2.local'
                    )
                )
            )
        );
        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('Config', $config);
        $adapterFactory = new MasterSlavesAdapterFactory();
        
        $adapter = $adapterFactory->createService($serviceManager);
        
        $this->assertInstanceOf('ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapter', $adapter);
        $this->assertInstanceOf('Zend\Db\Adapter\Adapter', $adapter->getSlaveAdapter());
        $this->assertNotInstanceOf('ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapter', $adapter->getSlaveAdapter());
    }
}
