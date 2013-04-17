<?php
namespace ZfPersistenceBase;

use Zend\Math\Rand;

class FakeRandomGenerator extends AbstractRandomGenerator
{
    public static $value = 1;
    
    protected function getInteger($min, $max, $strong = false)
    {
        return static::$value;
    }
}