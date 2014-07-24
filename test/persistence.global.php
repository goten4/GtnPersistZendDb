<?php
return array(
    'zenddb_repositories' => array(
        'CompanyRepository' => array(
            'table_name' => 'companies',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\Company',
            'aggregate_root_proxy_factory' => 'GtnPersistZendDbTest\Service\CompanyProxyFactory',
            'aggregate_root_hydrator_class' => 'GtnPersistZendDbTest\Infrastructure\ZendDb\CompanyHydrator',
        ),
        'UserRepository' => array(
            'factory' => 'GtnPersistZendDbTest\Infrastructure\ZendDb\UserRepositoryFactory',
            'table_name' => 'users',
            'table_id' => 'user_id',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\User',
            'aggregate_root_hydrator_class' => 'GtnPersistZendDbTest\Infrastructure\ZendDb\UserHydrator',
        ),
    ),
);
