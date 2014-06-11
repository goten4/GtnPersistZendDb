<?php
namespace GtnPersistZendDbTest\Db\Adapter;

use GtnPersistZendDb\Db\Adapter\MasterSlavesAdapter;
use GtnPersistZendDb\ZendRandomGenerator;
use GtnPersistZendDbTest\FakeRandomGenerator;
use Zend\Db\Adapter\Adapter;

class MasterSlavesAdapterTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canGetMasterAdapterWhenNoSlaves()
    {
        $masterAdapter = new MasterSlavesAdapter($this->adapterConfig('master.local'));
        $masterAdapter->setRandomGenerator(new ZendRandomGenerator());

        $slaveAdapter = $masterAdapter->getSlaveAdapter();

        $this->assertSame($masterAdapter, $slaveAdapter);
    }

    /** @test */
    public function canGetSlaveAdapterWhenOnlyOneSlave()
    {
        $masterAdapter = new MasterSlavesAdapter($this->adapterConfig('master.local'));
        $masterAdapter->setRandomGenerator(new ZendRandomGenerator());
        $expectedSlaveAdapter = new Adapter($this->adapterConfig('slave.local'));
        $masterAdapter->addSlaveAdapter($expectedSlaveAdapter);

        $slaveAdapter = $masterAdapter->getSlaveAdapter();

        $this->assertSame($expectedSlaveAdapter, $slaveAdapter);
    }

    /** @test */
    public function canGetRandomSlaveAdapter()
    {
        $masterAdapter = new MasterSlavesAdapter($this->adapterConfig('master.local'));
        $masterAdapter->setRandomGenerator(new FakeRandomGenerator(1));
        $masterAdapter->addSlaveAdapter(new Adapter($this->adapterConfig('slave1.local')));
        $masterAdapter->addSlaveAdapter(new Adapter($this->adapterConfig('slave2.local')));

        $slaveAdapter = $masterAdapter->getSlaveAdapter();

        $this->assertNotNull($slaveAdapter);
        $connectionParameters = $slaveAdapter->getDriver()->getConnection()->getConnectionParameters();
        $this->assertEquals('mysql:dbname=test;host=slave2.local', $connectionParameters['dsn']);
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
