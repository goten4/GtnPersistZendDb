<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'ZfPersistenceZendDb\Db\Adapter\MasterSlavesAdapterFactory',
        ),
        'invokables' => array(
            'ZfPersistence\RandomGenerator' => 'ZfPersistenceZendDb\ZendRandomGenerator',
        ),
    )
);
