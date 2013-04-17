<?php
return array(
    'db' => array(
        'master' => array(
            'driver' => 'Pdo',
            'dsn'    => 'mysql:dbname=test;host=master.local'
        ),
        'slaves' => array(
            array(
                'driver' => 'Pdo',
                'dsn'    => 'mysql:dbname=test;host=slave1.local'
            ),
            array(
                'driver' => 'Pdo',
                'dsn'    => 'mysql:dbname=test;host=slave2.local'
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapterFactory',
            'ZfPersistence\Repository' => 'ZfPersistenceZendDb\Infrastructure\ZendDbRepositoryFactory',
        ),
        'invokables' => array(
            'ZfPersistence\RandomGenerator' => 'ZfPersistenceZendDbTest\FakeRandomGenerator',
        ),
    ),
);