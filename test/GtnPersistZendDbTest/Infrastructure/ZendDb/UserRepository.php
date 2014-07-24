<?php
namespace GtnPersistZendDbTest\Infrastructure\ZendDb;

use GtnPersistZendDb\Infrastructure\ZendDb\Repository;
use GtnPersistZendDbTest\Model\CompanyInterface;
use GtnPersistZendDbTest\Model\UserRepositoryInterface;

class UserRepository extends Repository implements UserRepositoryInterface
{
    /**
     * @param CompanyInterface $company
     * @return array
     */
    public function getAllByCompany(CompanyInterface $company)
    {
        return $this->getAllBy(array('company_id' => $company->getId()));
    }
}
