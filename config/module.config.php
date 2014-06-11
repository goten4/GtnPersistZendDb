<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'GtnPersistZendDb\Db\Adapter\MasterSlavesAdapterFactory',
        ),
        'invokables' => array(
            'GtnPersist\RandomGenerator' => 'GtnPersistZendDb\ZendRandomGenerator',
        ),
        'abstract_factories' => array(
            'GtnPersistZendDb\Service\ZendDbRepositoryAbstractFactory',
        ),
    )
);
