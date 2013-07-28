<?php
namespace ZfPersistenceZendDbTest\Infrastructure;

use ZfPersistenceZendDb\Infrastructure\ZendDbRepository;

class ZendDbUserRepository extends ZendDbRepository
{
    protected function tableName()
    {
        return 'users';
    }

    protected function aggregateRootClassName()
    {
        return 'ZfPersistenceBaseTest\Model\User';
    }
}
