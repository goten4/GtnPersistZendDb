<?php
namespace GtnPersistZendDb\Db\Adapter;

use GtnPersistZendDb\RandomGeneratorInterface;
use Zend\Db\Adapter\Adapter;

class MasterSlavesAdapter extends Adapter implements MasterSlavesAdapterInterface
{
    /**
     * @var array
     */
    protected $slaveAdapters = array();

    /**
     * @var RandomGeneratorInterface
     */
    protected $randomGenerator;

    /**
     * @return RandomGeneratorInterface
     */
    public function getRandomGenerator()
    {
        return $this->randomGenerator;
    }

    /**
     * @param RandomGeneratorInterface $randomGenerator
     * @return MasterSlavesAdapter
     */
    public function setRandomGenerator(RandomGeneratorInterface $randomGenerator)
    {
        $this->randomGenerator = $randomGenerator;
        return $this;
    }

    /**
     * @param Adapter $adapter
     * @return MasterSlavesAdapter
     */
    public function addSlaveAdapter(Adapter $adapter)
    {
        $this->slaveAdapters[] = $adapter;
        return $this;
    }

    /**
     * @return Adapter
     */
    public function getSlaveAdapter()
    {
        $slaveAdaptersCount = count($this->slaveAdapters);
        if ($slaveAdaptersCount == 0) {
            return $this;
        }
        $randomIndex = $this->getRandomGenerator()->getInteger(0, $slaveAdaptersCount - 1);
        return $this->slaveAdapters[$randomIndex];
    }

    /**
     * @return array
     */
    public function getSlaveAdapters()
    {
        return $this->slaveAdapters;
    }
}
