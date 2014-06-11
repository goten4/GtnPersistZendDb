<?php
namespace GtnPersistZendDbTest\Model;

use GtnPersistBase\Model\AggregateRootInterface;

class User implements AggregateRootInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Company
     */
    protected $company;

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
     * Get Company.
     *
     * @return \GtnPersistZendDbTest\Model\Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set Company.
     *
     * @param \GtnPersistZendDbTest\Model\Company $company
     * @return User
     */
    public function setCompany($company)
    {
        $this->company = $company;
        return $this;
    }
}
