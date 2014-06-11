<?php
namespace GtnPersistZendDbTest;

use GtnPersistZendDb\RandomGeneratorInterface;

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
