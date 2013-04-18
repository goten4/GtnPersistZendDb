<?php
namespace ZfPersistenceZendDbTest;

use ZfPersistenceZendDb\RandomGeneratorInterface;

class FakeRandomGenerator implements RandomGeneratorInterface
{
    private $value = 0;
    
    public function __construct($value)
    {
        $this->value = $value;
    }
    
    public function getInteger($min, $max, $strong = false)
    {
        return $this->value;
    }
}