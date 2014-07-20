<?php
namespace GtnPersistZendDbTest\Model;

use GtnPersistBase\Model\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * @param CompanyInterface $company
     * @return array
     */
    public function getAllByCompany(CompanyInterface $company);
}
