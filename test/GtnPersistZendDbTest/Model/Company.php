<?php
namespace GtnPersistZendDbTest\Model;

use GtnPersistBase\Model\AggregateRootInterface;

class Company implements AggregateRootInterface, CompanyInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /** @var array */
    protected $employees;

    public function __construct($name = null)
    {
        $this->setName($name);
    }

    /**
     * Get Id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Id.
     *
     * @param int $id
     * @return Company
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get Name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Name.
     *
     * @param string $name
     * @return Company
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get Employees.
     *
     * @return array
     */
    public function getEmployees()
    {
        return $this->employees;
    }

    /**
     * Set Employees.
     *
     * @param array $employees
     * @return Company
     */
    public function setEmployees($employees)
    {
        $this->employees = $employees;
        return $this;
    }
}
