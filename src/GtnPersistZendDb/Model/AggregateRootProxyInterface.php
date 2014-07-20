<?php
namespace GtnPersistZendDb\Model;

use GtnPersistBase\Model\AggregateRootInterface;

interface AggregateRootProxyInterface extends AggregateRootInterface
{
    /**
     * return AggregateRootInterface
     */
    public function getAggregateRoot();

    /**
     * @param AggregateRootInterface $aggregateRoot
     * @return AggregateRootProxyInterface
     */
    public function setAggregateRoot(AggregateRootInterface $aggregateRoot);
}
