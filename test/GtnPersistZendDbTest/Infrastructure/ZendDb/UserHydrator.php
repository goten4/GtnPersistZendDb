<?php
namespace GtnPersistZendDbTest\Infrastructure\ZendDb;

use Zend\Stdlib\Hydrator\HydratorInterface;

class UserHydrator implements HydratorInterface
{
    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     */
    public function extract($object)
    {
        return array(
            'user_id' => $object->getId(),
            'name' => $object->getName(),
        );
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array  $data
     * @param  object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setId($data['user_id']);
        $object->setName($data['name']);
        return $object;
    }
}
