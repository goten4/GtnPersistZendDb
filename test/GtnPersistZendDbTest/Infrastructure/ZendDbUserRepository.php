<?php
namespace GtnPersistZendDbTest\Infrastructure;

use GtnPersistZendDb\Infrastructure\ZendDbRepository;
use GtnPersistZendDbTest\Model\CompanyInterface;
use GtnPersistZendDbTest\Model\UserRepositoryInterface;

class ZendDbUserRepository extends ZendDbRepository implements UserRepositoryInterface
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
