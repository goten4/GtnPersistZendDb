<?php
namespace GtnPersistZendDbTest\Model;

interface CompanyInterface
{
    /**
     * Get Id.
     *
     * @return int
     */
    public function getId();

    /**
     * Set Id.
     *
     * @param int $id
     * @return Company
     */
    public function setId($id);

    /**
     * Get Name.
     *
     * @return string
     */
    public function getName();

    /**
     * Set Name.
     *
     * @param string $name
     * @return Company
     */
    public function setName($name);

    /**
     * Get Employees.
     *
     * @return array
     */
    public function getEmployees();

    /**
     * Set Employees.
     *
     * @param array $employees
     * @return Company
     */
    public function setEmployees($employees);
}
