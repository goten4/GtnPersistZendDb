<?php
return array(
    'zenddb_repositories' => array(
        'CompanyRepository' => array(
            'table_name' => 'companies',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\Company',
        ),
        'UserRepository' => array(
            'factory' => 'GtnPersistZendDbTest\Service\ZendDbUserRepositoryFactory',
            'table_name' => 'users',
            'table_id' => 'user_id',
            'aggregate_root_class' => 'GtnPersistZendDbTest\Model\User',
            'aggregate_root_hydrator_class' => 'GtnPersistZendDbTest\Infrastructure\UserHydrator',
        ),
    ),
);
