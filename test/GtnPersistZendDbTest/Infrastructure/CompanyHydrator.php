<?php
namespace GtnPersistZendDbTest\Infrastructure;

use Zend\Stdlib\Hydrator\HydratorInterface;

class CompanyHydrator implements HydratorInterface
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
            'id' => $object->getId(),
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
        $object->setId($data['id']);
        $object->setName($data['name']);
        return $object;
    }
}
