<?php
namespace GtnPersistZendDb\Service;

use GtnPersistBase\Model\AggregateRootInterface;
use GtnPersistZendDb\Db\Adapter\MasterSlavesAdapterInterface;
use GtnPersistZendDb\Exception\MissingConfigurationException;
use GtnPersistZendDb\Exception\UnexpectedValueException;
use GtnPersistZendDb\Infrastructure\ZendDbRepository;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbRepositoryFactory implements RepositoryFactoryInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ZendDbRepository
     * @throws UnexpectedValueException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var MasterSlavesAdapterInterface $dbAdapter */
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');

        /** @var ZendDbRepository $repository */
        $repositoryClass = $this->get('repository_class', 'GtnPersistZendDb\Infrastructure\ZendDbRepository');
        $repository = new $repositoryClass($dbAdapter);
        if (!$repository instanceof ZendDbRepository) {
            throw new UnexpectedValueException("$repositoryClass: repository_class must extend GtnPersistZendDb\\Infrastructure\\ZendDbRepository");
        }

        $repository->setConfig($this->getConfig());
        $repository->setTableName($this->getStrict('table_name'));
        $repository->setTableId($this->get('table_id', 'id'));

        $aggregateRootClass = $this->getStrict('aggregate_root_class');
        if (!new $aggregateRootClass instanceof AggregateRootInterface) {
            throw new UnexpectedValueException("$aggregateRootClass: aggregate_root_class must implement GtnPersistBase\\Model\\AggregateRootInterface");
        }
        $repository->setAggregateRootClass($aggregateRootClass);

        /** @var HydratorInterface $hydrator */
        $hydratorClass = $this->get('aggregate_root_hydrator_class', 'Zend\Stdlib\Hydrator\ClassMethods');
        $hydrator = new $hydratorClass;
        if (!$hydrator instanceof HydratorInterface) {
            throw new UnexpectedValueException("$hydratorClass: aggregate_root_hydrator_class must implement Zend\\Stdlib\\Hydrator\\HydratorInterface");
        }
        $repository->setAggregateRootHydrator($hydrator);

        return $repository;
    }

    /**
     * @param string $key
     * @param mixed  $defaultValue
     * @return mixed
     */
    public function get($key, $defaultValue = null)
    {
        return isset($this->config[$key]) ? $this->config[$key] : $defaultValue;
    }

    /**
     * @param $key
     * @return mixed
     * @throws MissingConfigurationException
     */
    public function getStrict($key)
    {
        if (!isset($this->config[$key])) {
            throw new MissingConfigurationException("$key is missing in repository configuration");
        }
        return $this->config[$key];
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     * @return RepositoryFactoryInterface
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }
}
