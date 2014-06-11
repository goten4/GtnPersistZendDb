<?php
namespace GtnPersistZendDb\Service;

use Zend\ServiceManager\FactoryInterface;

interface RepositoryFactoryInterface extends FactoryInterface
{
    /**
     * @param array $config
     * @return RepositoryFactoryInterface
     */
    public function setConfig(array $config);
}
