<?php

namespace Application\Model\AbstractModel\Concern;

trait Constants
{

    public static function getConstants($prefix = '', $suffix = '')
    {
        $reflection = new \ReflectionClass(static::class);

        $constants = $reflection->getConstants();

        foreach ($constants as $name => $value) {
            if (1 !== preg_match('/^' . $prefix . '/', $name)
                && 1 !== preg_match('/' . $suffix . '$/', $value)
            ) {
                unset($constants[$name]);
            }
        }

        return $constants;
    }

}