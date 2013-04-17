<?php
namespace ZfPersistenceBase;

abstract class AbstractRandomGenerator
{
    private static $instance;

    public static function init(AbstractRandomGenerator $randomGenerator)
    {
        self::$instance = $randomGenerator;
    }

    /**
     * Generate a random integer between $min and $max
     *
     * @param  integer $min
     * @param  integer $max
     * @param  bool $strong true if you need a strong random generator (cryptography)
     * @return integer
     */
    public static function getInt()
    {
        return self::$instance->getInteger();
    }

    protected abstract function getInteger($min, $max, $strong = false);
}
