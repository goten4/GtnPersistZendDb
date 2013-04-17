<?php
namespace ZfPersistenceBase;

use Zend\Math\Rand;

class ZendMathRandomGenerator extends AbstractRandomGenerator
{
    protected function getInteger($min, $max, $strong = false)
    {
        return Rand::getInteger($min, $max, $strong);
    }
}