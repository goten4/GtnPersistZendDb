<?php
namespace GtnPersistZendDb\Service;

use GtnPersistBase\Model\AggregateRootInterface;
use GtnPersistZendDb\Model\AggregateRootProxyInterface;
use Zend\ServiceManager\ServiceManagerAwareInterface;

interface AggregateRootProxyFactoryInterface extends ServiceManagerAwareInterface
{
    /**
     * @param AggregateRootInterface $aggregateRoot
     * @return AggregateRootProxyInterface
     */
    public function createProxy(AggregateRootInterface $aggregateRoot);

    /**
     * @param $config
     * @return AggregateRootProxyFactoryInterface
     */
    public function setConfig($config);
}
