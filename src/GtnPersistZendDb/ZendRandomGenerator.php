<?php
namespace GtnPersistZendDb;

use Zend\Math\Rand;

class ZendRandomGenerator implements RandomGeneratorInterface
{
    /**
     * Generate a random integer between $min and $max
     *
     * @param  integer $min
     * @param  integer $max
     * @param  bool $strong true if you need a strong random generator (cryptography)
     * @return integer
     */
    public function getInteger($min, $max, $strong = false)
    {
        return Rand::getInteger($min, $max, $strong);
    }
}
