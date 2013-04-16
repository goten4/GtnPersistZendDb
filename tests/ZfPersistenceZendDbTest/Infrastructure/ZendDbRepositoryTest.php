<?php
namespace ZfPersistenceZendDbTest\Infrastructure;

use ZfPersistenceBaseTest\Infrastructure\AbstractRepositoryTest;
use ZfPersistenceZendDb\Infrastructure\ZendDbRepository;

class ZendDbRepositoryTest //extends AbstractRepositoryTest
{
    protected function _createRepository()
    {
        return new ZendDbRepository();
    }
}
