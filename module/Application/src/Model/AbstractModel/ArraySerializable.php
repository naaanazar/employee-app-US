<?php

namespace Application\Model\AbstractModel;

/**
 * Class ArraySerializable
 * @package Application\Model\AbstractModel
 */
abstract class ArraySerializable
{

    /**
     * @return array
     */
    abstract public function toArray(): array;

}