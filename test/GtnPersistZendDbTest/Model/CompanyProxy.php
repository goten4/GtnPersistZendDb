<?php
namespace GtnPersistZendDbTest\Model;

use GtnPersistBase\Model\AggregateRootInterface;
use GtnPersistZendDb\Model\AggregateRootProxyInterface;

class CompanyProxy implements CompanyInterface, AggregateRootProxyInterface
{
    /** @var Company */
    protected $aggregateRoot;

    /** @var UserRepositoryInterface */
    protected $userRepository;

    /**
     * Get Id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->aggregateRoot->getId();
    }

    /**
     * Set Id.
     *
     * @param int $id
     * @return Company
     */
    public function setId($id)
    {
        $this->aggregateRoot->setId($id);
        return $this;
    }

    /**
     * Get Name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->aggregateRoot->getName();
    }

    /**
     * Set Name.
     *
     * @param string $name
     * @return Company
     */
    public function setName($name)
    {
        $this->aggregateRoot->setName($name);
        return $this;
    }

    /**
     * Get Employees.
     *
     * @return array
     */
    public function getEmployees()
    {
        if ($this->aggregateRoot->getEmployees() !== null) {
            $this->aggregateRoot->setEmployees($this->userRepository->getAllByCompany($this->aggregateRoot));
        }
        return $this->aggregateRoot->getEmployees();
    }

    /**
     * Set Employees.
     *
     * @param array $employees
     * @return Company
     */
    public function setEmployees($employees)
    {
        $this->aggregateRoot->setEmployees($employees);
        return $this;
    }

    /**
     * return AggregateRootInterface
     */
    public function getAggregateRoot()
    {
        return $this->aggregateRoot;
    }

    /**
     * @param AggregateRootInterface $aggregateRoot
     * @return AggregateRootProxyInterface
     */
    public function setAggregateRoot(AggregateRootInterface $aggregateRoot)
    {
        $this->aggregateRoot = $aggregateRoot;
        return $this;
    }

    /**
     * Get UserRepository.
     *
     * @return \GtnPersistZendDbTest\Model\UserRepositoryInterface
     */
    public function getUserRepository()
    {
        return $this->userRepository;
    }

    /**
     * Set UserRepository.
     *
     * @param \GtnPersistZendDbTest\Model\UserRepositoryInterface $userRepository
     * @return CompanyProxy
     */
    public function setUserRepository($userRepository)
    {
        $this->userRepository = $userRepository;
        return $this;
    }
}
