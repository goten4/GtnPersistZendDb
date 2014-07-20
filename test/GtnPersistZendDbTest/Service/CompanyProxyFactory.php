<?php
namespace GtnPersistZendDbTest\Service;

use GtnPersistBase\Model\AggregateRootInterface;
use GtnPersistZendDb\Model\AggregateRootProxyInterface;
use GtnPersistZendDb\Service\AggregateRootProxyFactoryInterface;
use GtnPersistZendDbTest\Model\CompanyProxy;
use GtnPersistZendDbTest\Model\UserRepositoryInterface;
use Zend\ServiceManager\ServiceManager;

class CompanyProxyFactory implements AggregateRootProxyFactoryInterface
{
    /** @var array */
    protected $config;

    /** @var ServiceManager */
    protected $serviceManager;

    /**
     * Get Config.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param $config
     * @return AggregateRootProxyFactoryInterface
     */
    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Get ServiceManager.
     *
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @param AggregateRootInterface $aggregateRoot
     * @return AggregateRootProxyInterface
     */
    public function createProxy(AggregateRootInterface $aggregateRoot)
    {
        $companyProxy = new CompanyProxy();
        $companyProxy->setAggregateRoot($aggregateRoot);
        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->serviceManager->get('UserRepository');
        $companyProxy->setUserRepository($userRepository);
        return $companyProxy;
    }
}
